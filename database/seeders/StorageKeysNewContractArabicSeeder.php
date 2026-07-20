<?php

namespace Database\Seeders;

use App\Models\ContractTemplate;
use Illuminate\Database\Seeder;

class StorageKeysNewContractArabicSeeder extends Seeder
{
    /**
     * Seed the Storage Keys New Contract & Terms & Condition Arabic template.
     */
    public function run(): void
    {
        $path = database_path('data/contract-templates/storage_keys_new_contract_arabic.html');

        if (! is_file($path)) {
            $this->command?->error("Template HTML not found: {$path}");

            return;
        }

        $body = file_get_contents($path);
        $title = 'Storage Keys New Contract & Terms & Condition Arabic';

        $template = ContractTemplate::query()
            ->where('temp_title', $title)
            ->where('is_deleted', 0)
            ->first();

        if ($template) {
            $template->temp_body = $body;
            $template->status = 1;
            $template->save();
            $this->command?->info("Updated contract template #{$template->id}: {$title}");

            return;
        }

        $template = new ContractTemplate();
        $template->temp_title = $title;
        $template->temp_body = $body;
        $template->status = 1;
        $template->is_deleted = 0;
        $template->save();

        $this->command?->info("Created contract template #{$template->id}: {$title}");
    }
}
