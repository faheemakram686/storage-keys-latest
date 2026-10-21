<?php

namespace App\Services;

use App\Models\CustomerPaymentMethod;
use App\Models\Invoice;
use App\Models\InvoicePaymentAttempt;
use App\Models\Payment;
use App\Notifications\Backend\RecurringChargeFailedNotification;
use App\Repo\Interfaces\InvoiceInterface;
use App\Repo\PaymentClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class InvoiceNgeniusPaymentService
{
    public const MAX_RECURRING_CHARGE_ATTEMPTS = 3;

    public function __construct(
        protected NgeniusPaymentService $paymentService,
        protected InvoiceInterface $invoiceRepo,
        protected ZapierService $zapier
    ) {
    }

    /**
     * Record a new payment attempt and keep invoices.invoice_ref as the latest reference.
     */
    public function recordAttempt(
        Invoice $invoice,
        string $orderReference,
        float $expectedAmount,
        string $source,
        bool $saveCard = false,
        string $currency = 'AED'
    ): InvoicePaymentAttempt {
        $attempt = InvoicePaymentAttempt::create([
            'invoice_id' => $invoice->id,
            'order_reference' => $orderReference,
            'expected_amount' => $expectedAmount,
            'currency' => $currency,
            'state' => InvoicePaymentAttempt::STATE_CREATED,
            'source' => $source,
            'save_card' => $saveCard,
        ]);

        $this->invoiceRepo->updateInvoiceRef((object) [
            'id' => $invoice->id,
            'invoice_ref' => $orderReference,
        ]);

        return $attempt;
    }

    /**
     * Confirm an invoice payment from a gateway order response and optionally save the card token.
     *
     * @return array{success: bool, payment: mixed|null, amount: float|null, message: string, status?: string}
     */
    public function confirmFromOrderResponse(Invoice $invoice, $orderResponse, string $note = 'Paid via N-Genius'): array
    {
        if ((int) ($invoice->is_deleted ?? 0) === 1) {
            return [
                'success' => false,
                'payment' => null,
                'amount' => null,
                'message' => 'Invoice deleted',
                'status' => 'failed',
            ];
        }

        $orderReference = (string) ($orderResponse['reference'] ?? $invoice->invoice_ref ?? '');
        $attempt = $orderReference !== ''
            ? InvoicePaymentAttempt::findByOrderReference($orderReference)
            : null;

        if (!$this->paymentService->isPaid($orderResponse)) {
            $state = $this->paymentService->extractPaymentState($orderResponse);
            if ($attempt) {
                if ($this->paymentService->isPendingState($state)) {
                    $attempt->state = InvoicePaymentAttempt::STATE_PENDING;
                } elseif ($this->paymentService->isFailedState($state)) {
                    $attempt->state = InvoicePaymentAttempt::STATE_FAILED;
                }
                $attempt->save();
            }

            return [
                'success' => false,
                'payment' => null,
                'amount' => null,
                'message' => 'Payment not completed',
                'status' => $this->paymentService->isPendingState($state) ? 'pending' : 'failed',
                'payment_state' => $state,
            ];
        }

        $currency = $this->paymentService->extractCurrency($orderResponse) ?: 'AED';
        if ($currency !== 'AED') {
            Log::warning('N-Genius payment currency mismatch', [
                'invoice_id' => $invoice->id,
                'currency' => $currency,
                'reference' => $orderReference,
            ]);
            return [
                'success' => false,
                'payment' => null,
                'amount' => null,
                'message' => 'Unsupported currency',
                'status' => 'failed',
            ];
        }

        $capturedMinor = $this->paymentService->extractCapturedAmount($orderResponse);
        $amountReceived = $capturedMinor !== null
            ? round($capturedMinor / 100, 2)
            : null;

        if ($amountReceived === null || $amountReceived <= 0) {
            return [
                'success' => false,
                'payment' => null,
                'amount' => null,
                'message' => 'Invalid captured amount',
                'status' => 'failed',
            ];
        }

        try {
            $result = DB::transaction(function () use ($invoice, $orderResponse, $orderReference, $amountReceived, $note, $attempt) {
                $locked = Invoice::where('id', $invoice->id)->lockForUpdate()->first();
                if (!$locked || (int) $locked->is_deleted === 1) {
                    return [
                        'success' => false,
                        'payment' => null,
                        'amount' => null,
                        'message' => 'Invoice deleted',
                        'status' => 'failed',
                    ];
                }

                if ($orderReference !== '') {
                    $existing = Payment::query()
                        ->where('gateway_order_reference', $orderReference)
                        ->where('is_deleted', 0)
                        ->lockForUpdate()
                        ->first();

                    if ($existing) {
                        if ($attempt) {
                            $attempt->state = InvoicePaymentAttempt::STATE_PAID;
                            $attempt->save();
                        }
                        $this->storeSavedCardFromOrder($locked, $orderResponse, $attempt);

                        return [
                            'success' => true,
                            'payment' => $existing,
                            'amount' => (float) $existing->amount_received,
                            'message' => 'Payment already recorded',
                            'status' => 'paid',
                        ];
                    }
                }

                $locked->load(array_keys(Invoice::paymentStatusRelations()));
                $balance = $locked->balanceAmount();
                $paymentNote = $note;
                if ($amountReceived > $balance + 0.009) {
                    $paymentNote = trim($note . ' [REVIEW: captured amount exceeds invoice balance; possible duplicate charge]');
                }

                $paymentPayload = (object) [
                    'invoice_ref' => $orderReference !== '' ? $orderReference : ($locked->invoice_ref ?: null),
                    'invoice_id' => $locked->id,
                    'customer_id' => $locked->customer_id,
                    'contract_id' => $locked->contract_id,
                    'order_id' => $locked->order_id,
                    'payment_method' => 3,
                    'payment_date' => now(),
                    'amount_received' => $amountReceived,
                    'note' => $paymentNote,
                    'gateway_order_reference' => $orderReference !== '' ? $orderReference : null,
                ];

                $paymentClass = new PaymentClass();
                $paymentResponse = $paymentClass->savePayment($paymentPayload);
                $responseData = $paymentResponse ? $paymentResponse->getData() : null;
                $payment = $responseData->data ?? null;

                if (!$payment) {
                    return [
                        'success' => false,
                        'payment' => null,
                        'amount' => $amountReceived,
                        'message' => 'Payment save failed',
                        'status' => 'failed',
                    ];
                }

                if ($attempt) {
                    $attempt->state = InvoicePaymentAttempt::STATE_PAID;
                    $attempt->save();
                }

                $this->storeSavedCardFromOrder($locked, $orderResponse, $attempt);

                return [
                    'success' => true,
                    'payment' => $payment,
                    'amount' => $amountReceived,
                    'message' => 'Payment recorded',
                    'status' => 'paid',
                    '_qb' => true,
                ];
            });
        } catch (\Throwable $e) {
            Log::error('N-Genius payment confirmation failed', [
                'invoice_id' => $invoice->id,
                'reference' => $orderReference,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'payment' => null,
                'amount' => null,
                'message' => 'Payment save failed',
                'status' => 'failed',
            ];
        }

        if (!empty($result['_qb']) && !empty($result['payment'])) {
            $paymentClass = new PaymentClass();
            $qbPayload = $paymentClass->savePaymentToQuickbook($result['payment']);
            if ($qbPayload) {
                $this->zapier->send('add_payment', $qbPayload);
            }
            unset($result['_qb']);
        }

        if (!empty($result['success'])) {
            $this->invoiceRepo->syncPaymentStatus($invoice->id);
        }

        return $result;
    }

    /**
     * Confirm payment by fetching order status for the given reference.
     */
    public function confirmByReference(Invoice $invoice, string $reference, string $note = 'Paid via N-Genius'): array
    {
        $order = $this->paymentService->getOrderStatus($reference);
        if (!$order) {
            return [
                'success' => false,
                'payment' => null,
                'amount' => null,
                'message' => 'Unable to verify payment',
                'status' => 'error',
            ];
        }

        if (empty($invoice->invoice_ref)) {
            $invoice->invoice_ref = $reference;
            $invoice->save();
        }

        return $this->confirmFromOrderResponse($invoice, $order, $note);
    }

    /**
     * Resolve invoice + attempt from an order reference (attempts table first).
     *
     * @return array{invoice: Invoice|null, attempt: InvoicePaymentAttempt|null}
     */
    public function resolveByOrderReference(string $reference): array
    {
        $attempt = InvoicePaymentAttempt::findByOrderReference($reference);
        if ($attempt) {
            $invoice = Invoice::where('id', $attempt->invoice_id)->where('is_deleted', 0)->first();
            return ['invoice' => $invoice, 'attempt' => $attempt];
        }

        $invoice = Invoice::where('invoice_ref', $reference)->where('is_deleted', 0)->first();

        return ['invoice' => $invoice, 'attempt' => null];
    }

    /**
     * Public status for the processing page / polling endpoint.
     *
     * @return array{status: string, message: string, invoice_id?: int}
     */
    public function statusForReference(string $reference): array
    {
        $resolved = $this->resolveByOrderReference($reference);
        $invoice = $resolved['invoice'];
        $attempt = $resolved['attempt'];

        if (!$invoice) {
            return ['status' => 'failed', 'message' => 'Unknown payment reference'];
        }

        if ($attempt && $attempt->state === InvoicePaymentAttempt::STATE_PAID) {
            return ['status' => 'paid', 'message' => 'Payment recorded', 'invoice_id' => $invoice->id];
        }

        if ($reference !== '' && Payment::where('gateway_order_reference', $reference)->where('is_deleted', 0)->exists()) {
            return ['status' => 'paid', 'message' => 'Payment recorded', 'invoice_id' => $invoice->id];
        }

        $result = $this->confirmByReference($invoice, $reference, 'Paid via N-Genius (status check)');

        if (!empty($result['success'])) {
            return ['status' => 'paid', 'message' => $result['message'], 'invoice_id' => $invoice->id];
        }

        if (($result['status'] ?? '') === 'pending'
            || $this->paymentService->isPendingState($result['payment_state'] ?? null)) {
            return ['status' => 'pending', 'message' => 'Payment still processing', 'invoice_id' => $invoice->id];
        }

        if (($result['status'] ?? '') === 'error' || ($result['message'] ?? '') === 'Unable to verify payment') {
            return ['status' => 'pending', 'message' => 'Unable to verify payment yet', 'invoice_id' => $invoice->id];
        }

        return ['status' => 'failed', 'message' => $result['message'] ?? 'Payment failed', 'invoice_id' => $invoice->id];
    }

    public function storeSavedCardFromOrder(Invoice $invoice, $orderResponse, ?InvoicePaymentAttempt $attempt = null): void
    {
        $saveCard = $attempt ? (bool) $attempt->save_card : false;
        if (!$saveCard) {
            return;
        }

        $saved = $this->paymentService->extractSavedCard($orderResponse);
        if (!$saved || !$invoice->customer_id) {
            return;
        }

        try {
            CustomerPaymentMethod::upsertFromSavedCard((int) $invoice->customer_id, $saved, true);
        } catch (\Throwable $e) {
            Log::error('Failed to store N-Genius saved card', [
                'customer_id' => $invoice->customer_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Attempt an unattended charge for a recurring child invoice.
     */
    public function chargeRecurringInvoice(Invoice $child): array
    {
        $method = CustomerPaymentMethod::chargeableForCustomer((int) $child->customer_id);
        if (!$method) {
            return [
                'success' => false,
                'skipped' => true,
                'message' => 'No chargeable saved card',
            ];
        }

        $child->loadMissing(['customer.primaryContact']);
        $customer = $child->customer;
        if (!$customer) {
            return [
                'success' => false,
                'skipped' => true,
                'message' => 'Customer missing',
            ];
        }

        $balance = $child->balanceAmount();
        if ($balance <= 0) {
            return [
                'success' => true,
                'skipped' => true,
                'message' => 'No balance',
            ];
        }

        $attempts = (int) ($child->recurring_charge_attempts ?? 0);
        if ($attempts >= self::MAX_RECURRING_CHARGE_ATTEMPTS) {
            return [
                'success' => false,
                'skipped' => true,
                'message' => 'Max recurring charge attempts reached',
            ];
        }

        $cancelUrl = url('invoice-to-customer/' . hashid_encode($child->id));
        $chargeResult = $this->paymentService->chargeSavedCard(
            $balance,
            'AED',
            $customer,
            $method,
            [
                'merchant_order_reference' => $this->paymentService->uniqueMerchantOrderReference(
                    $child->invoice_no,
                    (int) $child->id
                ),
                'cancel_url' => $cancelUrl,
                'skip_3ds' => true,
            ]
        );

        $child->recurring_charge_attempts = $attempts + 1;
        $child->last_recurring_charge_at = now();
        $child->save();

        if (!$chargeResult || empty($chargeResult['order'])) {
            Log::warning('Recurring N-Genius charge failed to start', [
                'invoice_id' => $child->id,
            ]);
            $this->notifyRecurringChargeFailed($child, 'Charge request failed');
            return [
                'success' => false,
                'skipped' => false,
                'message' => 'Charge request failed',
            ];
        }

        $orderReference = $chargeResult['order']['reference'] ?? null;
        $attempt = null;
        if ($orderReference) {
            $attempt = $this->recordAttempt(
                $child,
                $orderReference,
                $balance,
                InvoicePaymentAttempt::SOURCE_RECURRING,
                false
            );
        }

        $paymentResponse = $chargeResult['payment'] ?? false;
        $state = is_array($paymentResponse) ? ($paymentResponse['state'] ?? null) : null;

        if ($orderReference) {
            $order = $this->paymentService->getOrderStatus($orderReference);
            if ($order) {
                $state = $this->paymentService->extractPaymentState($order) ?: $state;
                if ($this->paymentService->isPaid($order)) {
                    $result = $this->confirmFromOrderResponse(
                        $child,
                        $order,
                        'Paid via saved card (recurring invoice)'
                    );
                    return array_merge($result, ['skipped' => false, 'payment_state' => $state]);
                }
            }
        }

        if (is_array($paymentResponse) && $this->paymentService->isPaidState($state)) {
            $pseudoOrder = [
                'reference' => $orderReference,
                'amount' => ['currencyCode' => 'AED'],
                '_embedded' => [
                    'payment' => [$paymentResponse],
                ],
            ];
            $result = $this->confirmFromOrderResponse(
                $child,
                $pseudoOrder,
                'Paid via saved card (recurring invoice)'
            );
            return array_merge($result, ['skipped' => false, 'payment_state' => $state]);
        }

        if ($attempt) {
            if ($this->paymentService->isPendingState($state)) {
                $attempt->state = InvoicePaymentAttempt::STATE_PENDING;
            } else {
                $attempt->state = InvoicePaymentAttempt::STATE_FAILED;
            }
            $attempt->save();
        }

        Log::info('Recurring N-Genius charge awaiting customer or failed', [
            'invoice_id' => $child->id,
            'state' => $state,
            'reference' => $orderReference,
        ]);

        $this->notifyRecurringChargeFailed($child, $state ? 'Payment state: ' . $state : 'Payment not completed');

        return [
            'success' => false,
            'skipped' => false,
            'message' => 'Payment not completed',
            'payment_state' => $state,
        ];
    }

    protected function notifyRecurringChargeFailed(Invoice $invoice, string $reason): void
    {
        try {
            $invoice->loadMissing(['customer.primaryContact']);
            $customer = $invoice->customer;
            $email = $customer?->primaryContact?->email ?: $customer?->email;
            if (!$email) {
                return;
            }

            $invoiceUrl = url('invoice-to-customer/' . hashid_encode($invoice->id));
            Notification::route('mail', $email)->notify(new RecurringChargeFailedNotification([
                'subject' => 'Action needed: payment for invoice ' . $invoice->invoice_no,
                'invoice_no' => $invoice->invoice_no,
                'invoice_url' => $invoiceUrl,
                'reason' => $reason,
                'amount' => $invoice->balanceAmount(),
            ]));
        } catch (\Throwable $e) {
            Log::error('Failed to send recurring charge failure email', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
