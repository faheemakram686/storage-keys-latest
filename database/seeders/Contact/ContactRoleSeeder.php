<?php

namespace Database\Seeders\Contact;

use App\Models\Contact;
use App\Models\ContactPermission;
use App\Models\ContactRole;
use App\Services\Contact\ContactRoleSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ContactRoleSeeder extends Seeder
{
    public function run()
    {
        if (!Schema::hasTable('contact_roles')) {
            return;
        }

        $permissions = [
            ['name' => 'view_portal', 'group_name' => 'portal', 'description' => 'Access customer dashboard'],
            ['name' => 'view_invoices', 'group_name' => 'billing', 'description' => 'View invoices'],
            ['name' => 'pay_invoices', 'group_name' => 'billing', 'description' => 'Pay invoices'],
            ['name' => 'view_contracts', 'group_name' => 'contracts', 'description' => 'View contracts'],
            ['name' => 'manage_contacts', 'group_name' => 'contacts', 'description' => 'Invite and manage contacts'],
            ['name' => 'update_account', 'group_name' => 'account', 'description' => 'Update company/billing account'],
        ];

        foreach ($permissions as $permission) {
            ContactPermission::query()->updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        $roles = [
            ContactRoleSyncService::OWNER => [
                'name' => 'Owner',
                'description' => 'Full customer portal access',
                'permissions' => [
                    'view_portal', 'view_invoices', 'pay_invoices', 'view_contracts',
                    'manage_contacts', 'update_account',
                ],
            ],
            ContactRoleSyncService::BILLING => [
                'name' => 'Billing',
                'description' => 'Can view and pay invoices',
                'permissions' => [
                    'view_portal', 'view_invoices', 'pay_invoices', 'view_contracts',
                ],
            ],
            ContactRoleSyncService::VIEWER => [
                'name' => 'Viewer',
                'description' => 'Read-only portal access',
                'permissions' => [
                    'view_portal', 'view_invoices', 'view_contracts',
                ],
            ],
        ];

        foreach ($roles as $alias => $config) {
            $role = ContactRole::query()->updateOrCreate(
                ['alias' => $alias],
                [
                    'name' => $config['name'],
                    'description' => $config['description'],
                ]
            );

            $ids = ContactPermission::query()
                ->whereIn('name', $config['permissions'])
                ->pluck('id')
                ->all();

            $role->permissions()->sync($ids);
        }

        $this->backfillExistingContacts();
    }

    protected function backfillExistingContacts(): void
    {
        if (!Schema::hasColumn('contacts', 'contact_role_id')) {
            return;
        }

        $ownerId = optional(ContactRole::findByAlias(ContactRoleSyncService::OWNER))->id;
        $viewerId = optional(ContactRole::findByAlias(ContactRoleSyncService::VIEWER))->id;

        if (!$ownerId || !$viewerId) {
            return;
        }

        Contact::query()
            ->whereNull('contact_role_id')
            ->where('is_deleted', 0)
            ->orderBy('id')
            ->chunkById(200, function ($contacts) use ($ownerId, $viewerId) {
                foreach ($contacts as $contact) {
                    $type = strtolower((string) ($contact->getAttributes()['contact_type'] ?? ''));
                    $contact->contact_role_id = $type === 'primary' ? $ownerId : $viewerId;
                    $contact->save();
                }
            });
    }
}
