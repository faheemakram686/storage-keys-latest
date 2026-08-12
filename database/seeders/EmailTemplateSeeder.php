<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Seed customer welcome and set-password email templates.
     */
    public function run(): void
    {
        $now = now();
        $templates = [
            [
                'temp_name' => 'Customer_welcome_email',
                'temp_for' => 'customer',
                'temp_cat' => 'email',
                'temp_subject' => 'Welcome to Storage Keys',
                'temp_body' => 'Welcome to Storage Keys',
                'status' => 1,
                'is_deleted' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'temp_name' => 'Customer_set_password_email',
                'temp_for' => 'customer',
                'temp_cat' => 'email',
                'temp_subject' => 'Set Your Password',
                'temp_body' => 'Welcome to Storage Keys Please Set Your Password',
                'status' => 1,
                'is_deleted' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($templates as $template) {
            $exists = DB::table('email_templates')
                ->where('temp_name', $template['temp_name'])
                ->where('temp_for', $template['temp_for'])
                ->where('temp_cat', $template['temp_cat'])
                ->where('is_deleted', 0)
                ->exists();

            if (!$exists) {
                DB::table('email_templates')->insert($template);
            }
        }
    }
}
