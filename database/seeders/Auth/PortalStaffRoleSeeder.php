<?php

namespace Database\Seeders\Auth;

use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\Type;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PortalStaffRoleSeeder extends Seeder
{
    public function run()
    {
        if (!Schema::hasTable('roles')) {
            return;
        }

        $adminType = Type::findByAlias('admin');
        if (!$adminType) {
            return;
        }

        $role = Role::query()->firstOrCreate(
            [
                'alias' => 'portal_staff',
                'type_id' => $adminType->id,
            ],
            [
                'name' => 'Portal Staff',
                'is_admin' => 0,
                'is_default' => 1,
                'created_by' => 1,
            ]
        );

        if ($role->name !== 'Portal Staff') {
            $role->name = 'Portal Staff';
            $role->save();
        }

        $permissionNames = [
            'view_customer', 'create_customer', 'edit_customer',
            'view_lead', 'view_estimate', 'view_contract', 'view_invoice',
            'edit_invoice', 'create_invoice',
            'view_order',
            'view_warehouse', 'view_storage_unit',
            'view_user', 'view_role',
        ];

        $ids = Permission::query()
            ->where('type_id', $adminType->id)
            ->whereIn('name', $permissionNames)
            ->pluck('id')
            ->all();

        // If some names missing, give all admin-type permissions that look like view_*
        if (count($ids) < 5) {
            $ids = Permission::query()
                ->where('type_id', $adminType->id)
                ->where(function ($q) {
                    $q->where('name', 'like', 'view_%')
                        ->orWhere('name', 'like', 'create_%')
                        ->orWhere('name', 'like', 'edit_%');
                })
                ->pluck('id')
                ->all();
        }

        $role->permissions()->sync($ids);
    }
}
