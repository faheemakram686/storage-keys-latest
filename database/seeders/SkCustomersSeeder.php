<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeds customers from SK_Customers_Details.pdf (parsed JSON).
 *
 * Live deploy:
 *   php artisan db:seed --class=Database\\Seeders\\SkCustomersSeeder
 *
 * Safe to re-run: skips existing emails (customers/contacts).
 */
class SkCustomersSeeder extends Seeder
{
    public function run()
    {
        $path = database_path('seeders/data/sk_customers.json');
        if (!is_file($path)) {
            $this->command?->error('Missing data file: database/seeders/data/sk_customers.json');

            return;
        }

        $rows = json_decode((string) file_get_contents($path), true);
        if (!is_array($rows)) {
            $this->command?->error('Invalid sk_customers.json');

            return;
        }

        $created = 0;
        $skippedExisting = 0;
        $skippedInvalid = 0;
        $errors = 0;

        $this->command?->info('Importing ' . count($rows) . ' customer rows from PDF seeder data...');

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
                $skippedExisting++;
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
                    $customer->status = 1;
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
                    $contact->status = 1;
                    $contact->is_deleted = 0;
                    $contact->save();

                    $created++;
                });
            } catch (\Throwable $e) {
                $errors++;
            }
        }

        $this->command?->info(json_encode([
            'source_rows' => count($rows),
            'created' => $created,
            'skipped_existing' => $skippedExisting,
            'skipped_invalid' => $skippedInvalid,
            'errors' => $errors,
        ]));
    }
}
