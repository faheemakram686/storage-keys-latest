<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repo\AppSettingsClass;
use App\Repo\ContactClass;
use App\Repo\ContractTemplateClass;
use App\Repo\CustomerClass;
use App\Repo\EstimateClass;
use App\Repo\Interfaces\ContractInterface;
use App\Repo\UserClass;
use Illuminate\Http\Request;
use function Symfony\Component\Translation\t;
use PDF;
use ArPHP\I18N\Arabic;

class ContractController extends Controller
{


    private $customer;
    private $estimate;
    private $contract;
    private  $user;
    private  $contract_template;
    private $contact ;
    private $appsettings ;

    public function __construct(ContractInterface $contract )
    {
        $this->contract = $contract;
        $this->customer = new CustomerClass();
        $this->estimate = new EstimateClass();
        $this->user = new UserClass();
        $this->contract_template = new ContractTemplateClass();
        $this->contact =  new ContactClass();
        $this->appsettings = new AppSettingsClass();
    }


    public function index()
    {
        return view('backend.contract.index');
    }


    public function createContract()
    {
        $data['customers'] = $this->customer->getAllCustomer();
        $data['estimates'] = $this->estimate->getAllEstimate();
        return view('backend.contract.create')->with(compact('data'));
    }

    public function saveContract(Request $request)
    {
        return $this->contract->saveContract($request);
    }
    public function getAllContracts()
    {
        return $this->contract->getAllContract();
    }
    public function deleteContract(Request $request)
    {
        $this->contract->deleteContract($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);
    }

    public function editContract($id)
    {
        $data['customers'] = $this->customer->getAllCustomer();
        $data['estimates'] = $this->estimate->getAllEstimate();
        $data['contract']= $this->contract->editContract($id);
        return view('backend.contract.edit')->with(compact('data'));
    }

    public function updateContract(Request $request)
    {

        $res = $this->contract->updateContract($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }

    public function updateContractId(Request $request)
    {
        $res = $this->contract->updateContractId($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }

    public function detailContract($id)
    {
        $data['contract'] = $this->contract->getContract($id);
        $data['contract_template'] = $this->contract_template->getAllTemplate();
        $data['appSettings'] = $this->appsettings->getAppSettings();
        return view('backend.contract.show')->with(compact('data'));
    }
    public function contractEstimate(Request $request)
    {
        $data['contract'] = $this->contract->getContract($request->contract_id);
        return $data;
    }

    public function getCustomerContracts(Request $request)
    {
        return $this->contract->getCustomerContracts($request->customer_id);
    }
    public function getCustomerContractsApi(Request $request)
    {
        return $this->contract->getCustomerContractsApi($request->customer_id);
    }

    public function contractToCustomer(Request $request)
    {
        $data['contract'] = $this->contract->getContract($request->id);
        $this->applyContractTemplateVariables($data['contract']);

        return view('backend.contract.contract-customer-view')->with(compact('data'));
    }

    public function contractPdf(Request $request)
    {
        $data['contract'] = $this->contract->getContract($request->id);
        $this->applyContractTemplateVariables($data['contract']);

        // Reshape Arabic only in the contract body (much faster than full document + CSS).
        if (!empty($data['contract'][0]->contractTemplate)) {
            $data['contract'][0]->contractTemplate->temp_body = $this->reshapeArabicHtml(
                (string) $data['contract'][0]->contractTemplate->temp_body
            );
        }

        $pdf = PDF::loadView('backend.contract.contract-customer-pdf', compact('data'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'isFontSubsettingEnabled' => true,
            ]);

        return $pdf->stream('contract-'.$request->id.'.pdf');
    }

    /**
     * Convert Arabic text segments to presentation forms for DomPDF.
     */
    protected function reshapeArabicHtml(string $html): string
    {
        if ($html === '' || !preg_match('/\p{Arabic}/u', $html)) {
            return $html;
        }

        try {
            $arabic = new Arabic();
            $positions = $arabic->arIdentify($html);

            for ($i = count($positions) - 1; $i >= 1; $i -= 2) {
                $start = (int) $positions[$i - 1];
                $end = (int) $positions[$i];
                $length = $end - $start;

                if ($length <= 0) {
                    continue;
                }

                $segment = substr($html, $start, $length);
                $html = substr_replace($html, $arabic->utf8Glyphs($segment), $start, $length);
            }
        } catch (\Throwable $e) {
            // Keep original HTML if reshaping fails.
        }

        return $html;
    }

    public function signContract(Request $request)
    {
        $res = $this->contract->signContact($request);
        return response()->json(['success' => 'Contract signed successfully'], 200);
    }

    public function showTasks($request)
    {
        $data['contract'] = $this->contract->getContract($request);
        $data['user'] = $this->user->getUser();
        return view('backend.contract.tasks')->with(compact('data'));
    }

    public function showAttachments($request)
    {
        $data['contract'] = $this->contract->getContract($request);
        $data['user'] = $this->user->getUser();
        return view('backend.contract.attachments')->with(compact('data'));
    }
    public function showTemplates($request)
    {
        $data['contract'] = $this->contract->getContract($request);
        $data['users'] = $this->user->getUser();
        return view('backend.contract.templates')->with(compact('data'));
    }

    public function approveContract(Request $request)
    {
        return $this->contract->approveContract($request->id);
    }
    public function declineContract(Request $request)
    {
        return $this->contract->declineContract($request);
    }
    public function showReminders($request)
    {
        $data['contract']=$this->contract->getContract($request);
        $data['users']=$this->user->getUser();
        return view('backend.contract.reminders')->with(compact('data'));
    }
    public function showNotes($request)
    {
        $data['contract']=$this->contract->getContract($request);
        $data['users']=$this->user->getUser();
        return view('backend.contract.notes')->with(compact('data'));
    }

    protected function applyContractTemplateVariables($contract): void
    {
        if (empty($contract[0]) || !$contract[0]->contractTemplate) {
            return;
        }

        $c = $contract[0];
        $templateContent = (string) ($c->contractTemplate->temp_body ?? '');
        $replacements = [];

        $customer = $c->customer;
        if ($customer) {
            foreach ($customer->getAttributes() as $key => $value) {
                if (in_array($key, ['password', 'remember_token'], true)) {
                    continue;
                }
                $replacements[$key] = $this->formatTemplateValue($value);
            }

            try {
                $contact = $this->contact->getPrimaryContect($customer->id);
            } catch (\Throwable $e) {
                $contact = null;
            }

            if ($contact) {
                foreach ($contact->getAttributes() as $key => $value) {
                    if (in_array($key, ['password', 'remember_token'], true)) {
                        continue;
                    }
                    $replacements['contact.'.$key] = $this->formatTemplateValue($value);
                }
                $replacements['contact.full_name'] = trim(
                    $this->formatTemplateValue($contact->first_name ?? null).' '.
                    $this->formatTemplateValue($contact->last_name ?? null)
                );
            }

            $customerType = strtolower(trim((string) ($customer->customer_type ?? '')));
            $replacements['individual_mark'] = $customerType === 'individual' ? '✓' : '';
            $replacements['business_mark'] = $customerType === 'company' ? '✓' : '';
            $replacements['customer_type'] = $this->formatTemplateValue($customer->customer_type ?? null);
        }

        $replacements['agreement_no'] = $this->formatTemplateValue($c->id);
        $replacements['contract_id'] = $this->formatTemplateValue($c->id);
        $replacements['contract_value'] = $this->formatMoneyValue($c->contract_value ?? null);
        $replacements['subject'] = $this->formatTemplateValue($c->subject ?? null);
        $replacements['start_date'] = $this->formatDateValue($c->start_date ?? null);
        $replacements['end_date'] = $this->formatDateValue($c->end_date ?? null);
        $replacements['commencement_date'] = $replacements['start_date'];
        $replacements['insurance_cover'] = $this->formatTemplateValue(
            $c->insurance_cover ?? optional($c->estimate)->insurance_cover ?? null
        );

        $bookedBy = '';
        $user = $c->relationLoaded('userRensonsible') ? $c->userRensonsible : null;
        if (!$user && !empty($c->user_id)) {
            try {
                $user = $c->userRensonsible()->first();
            } catch (\Throwable $e) {
                $user = null;
            }
        }
        if ($user) {
            $bookedBy = trim(
                $this->formatTemplateValue($user->first_name ?? null).' '.
                $this->formatTemplateValue($user->last_name ?? null)
            );
            if ($bookedBy === '' && !empty($user->name)) {
                $bookedBy = $this->formatTemplateValue($user->name);
            }
        }
        $replacements['booked_by'] = $bookedBy;

        $estimate = $c->estimate;
        $replacements['alt_contact_name'] = $this->formatTemplateValue(
            $c->alt_contact_name ?? optional($estimate)->alt_contact_name ?? null
        );
        $replacements['alt_contact_email'] = $this->formatTemplateValue(
            $c->alt_contact_email ?? optional($estimate)->alt_contact_email ?? null
        );
        $replacements['alt_contact_mobile'] = $this->formatTemplateValue(
            $c->alt_contact_mobile ?? optional($estimate)->alt_contact_mobile ?? null
        );

        $addonPrice = 0.0;
        $period = 1.0;
        $discount = 0.0;
        $storageFee = 0.0;
        $unitNames = [];
        $unitSizes = [];
        $unitCount = 0;
        $addonNames = [];

        if ($estimate) {
            $addons = $estimate->estimateAddon ?? collect([]);
            $addonPrice = (float) $addons->sum(function ($row) {
                return (float) ($row->price ?? 0);
            });
            foreach ($addons as $addonRow) {
                $addonName = optional($addonRow->addon)->name
                    ?? optional($addonRow->addon)->title
                    ?? null;
                if ($addonName) {
                    $addonNames[] = $addonName;
                }
            }

            $term = $estimate->termLength;
            $period = $term ? (float) ($term->term_period ?? 1) : 1.0;
            if ($period <= 0) {
                $period = 1.0;
            }
            $discount = $term ? (float) ($term->discount_percentage ?? 0) : 0.0;
            $replacements['term_title'] = $term ? $this->formatTemplateValue($term->title ?? null) : '';
            $replacements['term_period'] = $term ? $this->formatTemplateValue($term->term_period ?? null) : '';

            $estUnits = $estimate->estimateStorageUnits ?? collect([]);
            if ($estUnits->isEmpty()) {
                $price = (float) ($estimate->unit_price ?? 0);
                $storageTotal = $price * $period;
                $storageFee = $storageTotal - ($storageTotal * $discount / 100);
                $su = $estimate->storageunit;
                if ($su) {
                    $unitNames[] = $this->formatTemplateValue($su->storage_unit_name ?? null);
                    $unitSizes[] = $this->resolveUnitSizeLabel($su);
                    $unitCount = 1;
                }
            } else {
                foreach ($estUnits as $row) {
                    $price = (float) ($row->unit_price ?? 0);
                    $storageTotal = $price * $period;
                    $storageFee += $storageTotal - ($storageTotal * $discount / 100);
                    $su = $row->storageunit;
                    $unitNames[] = $su
                        ? $this->formatTemplateValue($su->storage_unit_name ?? null)
                        : ('#'.($row->storage_unit_id ?? ''));
                    $unitSizes[] = $su ? $this->resolveUnitSizeLabel($su) : '';
                }
                $unitCount = $estUnits->count();
            }
        } else {
            $replacements['term_title'] = '';
            $replacements['term_period'] = '';
        }

        $contractUnits = collect($c->contractStorageUnits ?? [])->filter(function ($row) {
            return empty($row->released_at);
        });
        if ($contractUnits->isNotEmpty()) {
            $unitNames = [];
            $unitSizes = [];
            foreach ($contractUnits as $row) {
                $su = $row->storageunit;
                $unitNames[] = $su
                    ? $this->formatTemplateValue($su->storage_unit_name ?? null)
                    : ('#'.($row->storage_unit_id ?? ''));
                $unitSizes[] = $su ? $this->resolveUnitSizeLabel($su) : '';
            }
            $unitCount = $contractUnits->count();
        }

        $insuranceMonthly = (float) (
            $c->insurance_amount
            ?? optional($estimate)->insurance_amount
            ?? 0
        );
        $insuranceFee = $insuranceMonthly * $period;
        $totalFee = $storageFee + $addonPrice + $insuranceFee;

        // Keep unit sizes in the same order/count as unit numbers (do not unique-collapse).
        $pairedUnitLabels = [];
        foreach ($unitNames as $index => $unitName) {
            if ($unitName === '' || $unitName === null) {
                continue;
            }
            $pairedUnitLabels[] = [
                'name' => $unitName,
                'size' => $unitSizes[$index] ?? '',
            ];
        }
        $replacements['unit_no'] = implode(', ', array_column($pairedUnitLabels, 'name'));
        $replacements['unit_size'] = implode(', ', array_map(
            fn ($row) => $row['size'] !== '' ? $row['size'] : '-',
            $pairedUnitLabels
        ));
        $replacements['total_units'] = $unitCount > 0 ? (string) $unitCount : '';
        $replacements['storage_fee'] = $this->formatMoneyValue($storageFee);
        $replacements['addon_fee'] = $this->formatMoneyValue($addonPrice);
        $replacements['addon_name'] = implode(', ', array_values(array_unique(array_filter($addonNames)))) ?: 'Padlock';
        $replacements['insurance_fee'] = $this->formatMoneyValue($insuranceFee);
        $replacements['total'] = $this->formatMoneyValue($totalFee);
        $replacements['total_fee'] = $replacements['total'];

        foreach ($replacements as $key => $value) {
            $templateContent = str_replace('{{'.$key.'}}', $value, $templateContent);
        }

        // Leave blank instead of showing unresolved tokens when related data is missing.
        $templateContent = preg_replace('/\{\{[a-zA-Z0-9_.]+\}\}/', '', $templateContent) ?? $templateContent;

        $c->contractTemplate->temp_body = $templateContent;
    }

    protected function resolveUnitSizeLabel($storageUnit): string
    {
        if (!$storageUnit) {
            return '';
        }

        $typeName = optional($storageUnit->storagesize)->unit_type_name;
        if ($typeName !== null && trim((string) $typeName) !== '') {
            return trim((string) $typeName);
        }

        $parts = array_filter([
            $storageUnit->width ?? null,
            $storageUnit->length ?? null,
            $storageUnit->height ?? null,
        ], function ($v) {
            return $v !== null && $v !== '';
        });

        if (count($parts) === 0) {
            return '';
        }

        return implode(' x ', $parts);
    }

    protected function formatTemplateValue($value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if (is_scalar($value)) {
            return trim((string) $value);
        }

        return '';
    }

    protected function formatDateValue($value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        try {
            if ($value instanceof \DateTimeInterface) {
                return $value->format('d M Y');
            }

            return \Carbon\Carbon::parse($value)->format('d M Y');
        } catch (\Throwable $e) {
            return $this->formatTemplateValue($value);
        }
    }

    protected function formatMoneyValue($value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        if (!is_numeric($value)) {
            return $this->formatTemplateValue($value);
        }

        return number_format((float) $value, 2, '.', '');
    }

}
