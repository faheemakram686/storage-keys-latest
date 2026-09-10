<?php

namespace App\Console\Commands;

use App\Models\Contact;
use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportSkCustomersFromPdf extends Command
{
    protected $signature = 'customers:import-sk-pdf
        {--file= : Path to JSON file (default storage/app/sk_customers_import.json)}
        {--dry-run : Parse/count only, do not write}';

    protected $description = 'Import StorageKeys customers from parsed SK_Customers_Details.pdf JSON (no PII logged)';

    public function handle(): int
    {
        $path = $this->option('file') ?: storage_path('app/sk_customers_import.json');
        if (!is_file($path)) {
            $this->error('JSON file not found: ' . $path);
            $this->line('Run: python scripts/parse_sk_customers_pdf.py');

            return self::FAILURE;
        }

        $rows = json_decode((string) file_get_contents($path), true);
        if (!is_array($rows)) {
            $this->error('Invalid JSON');

            return self::FAILURE;
        }

        $dry = (bool) $this->option('dry-run');
        $created = 0;
        $skippedExisting = 0;
        $skippedInvalid = 0;
        $errors = 0;

        foreach ($rows as $row) {
            $email = strtolower(trim((string) ($row['email'] ?? '')));
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skippedInvalid++;
                continue;
            }

            $exists = Customer::query()
                ->where('is_deleted', 0)
                ->whereRaw('LOWER(email) = ?', [$email])
                ->exists();

            if (!$exists) {
                $exists = Contact::query()
                    ->where('is_deleted', 0)
                    ->whereRaw('LOWER(email) = ?', [$email])
                    ->exists();
            }

            if ($exists) {
                if (!$dry) {
                    Customer::query()
                        ->where('is_deleted', 0)
                        ->whereRaw('LOWER(email) = ?', [$email])
                        ->update(['status' => 0]);
                    Contact::query()
                        ->where('is_deleted', 0)
                        ->whereRaw('LOWER(email) = ?', [$email])
                        ->update(['status' => 0]);
                }
                $skippedExisting++;
                continue;
            }

            if ($dry) {
                $created++;
                continue;
            }

            try {
                DB::transaction(function () use ($row, $email, &$created) {
                    $type = ($row['customer_type'] ?? 'individual') === 'company' ? 'company' : 'individual';
                    $first = trim((string) ($row['first_name'] ?? 'Customer'));
                    $last = trim((string) ($row['last_name'] ?? $first));
                    if ($first === '') {
                        $first = 'Customer';
                    }
                    if ($last === '') {
                        $last = $first;
                    }

                    $customer = new Customer();
                    $customer->customer_type = $type;
                    $customer->customer_name = trim((string) ($row['customer_name'] ?? ($first . ' ' . $last)));
                    $customer->company_name = $type === 'company'
                        ? (trim((string) ($row['company_name'] ?? $customer->customer_name)) ?: $customer->customer_name)
                        : null;
                    $customer->email = $email;
                    $customer->phone = $row['phone'] ?? null;
                    $customer->mobile = $row['phone'] ?? null;
                    $customer->address = $row['address'] ?? null;
                    $customer->city = $row['city'] ?? null;
                    $customer->state = $row['state'] ?? null;
                    $customer->country = $row['country'] ?? 'United Arab Emirates';
                    $customer->status = 0; // In-Active
                    $customer->is_deleted = 0;
                    $customer->save();

                    $contact = new Contact();
                    $contact->customer_id = $customer->id;
                    $contact->first_name = $first;
                    $contact->last_name = $last;
                    $contact->position = 'owner';
                    $contact->email = $email;
                    $contact->phone = $row['phone'] ?? null;
                    $contact->contact_type = 'primary';
                    $contact->status = 0;
                    $contact->is_deleted = 0;
                    $contact->save();

                    $created++;
                });
            } catch (\Throwable $e) {
                $errors++;
            }
        }

        $this->info(json_encode([
            'dry_run' => $dry,
            'source_rows' => count($rows),
            'created' => $created,
            'skipped_existing' => $skippedExisting,
            'skipped_invalid' => $skippedInvalid,
            'errors' => $errors,
        ]));

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }
}
