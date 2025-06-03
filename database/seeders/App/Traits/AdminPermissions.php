<?php


namespace Database\Seeders\App\Traits;


trait AdminPermissions
{
    public function adminpermissions($admin, $app): array
    {
        return [
            [
                'name' => 'create_user',
                'type_id' => $admin,
                'group_name' => 'user'
            ],
            [
                'name' => 'view_user',
                'type_id' => $admin,
                'group_name' => 'user'
            ],
            [
                'name' => 'edit_user',
                'type_id' => $admin,
                'group_name' => 'user'
            ],
            [
                'name' => 'delete_user',
                'type_id' => $admin,
                'group_name' => 'user'
            ],
            [
                'name' => 'create_role',
                'type_id' => $admin,
                'group_name' => 'role'
            ],
            [
                'name' => 'view_role',
                'type_id' => $admin,
                'group_name' => 'role'
            ],
            [
                'name' => 'edit_role',
                'type_id' => $admin,
                'group_name' => 'role'
            ],
            [
                'name' => 'delete_role',
                'type_id' => $admin,
                'group_name' => 'role'
            ],
            [
                'name' => 'create_lead',
                'type_id' => $admin,
                'group_name' => 'lead'
            ],
            [
                'name' => 'view_lead',
                'type_id' => $admin,
                'group_name' => 'lead'
            ],
            [
                'name' => 'edit_lead',
                'type_id' => $admin,
                'group_name' => 'lead'
            ],
            [
                'name' => 'delete_lead',
                'type_id' => $admin,
                'group_name' => 'lead'
            ],
            [
                'name' => 'create_estimate',
                'type_id' => $admin,
                'group_name' => 'estimate'
            ],
            [
                'name' => 'view_estimate',
                'type_id' => $admin,
                'group_name' => 'estimate'
            ],
            [
                'name' => 'edit_estimate',
                'type_id' => $admin,
                'group_name' => 'estimate'
            ],
            [
                'name' => 'delete_estimate',
                'type_id' => $admin,
                'group_name' => 'estimate'
            ],
            [
                'name' => 'create_contract',
                'type_id' => $admin,
                'group_name' => 'contract'
            ],
            [
                'name' => 'view_contract',
                'type_id' => $admin,
                'group_name' => 'contract'
            ],
            [
                'name' => 'edit_contract',
                'type_id' => $admin,
                'group_name' => 'contract'
            ],
            [
                'name' => 'delete_contract',
                'type_id' => $admin,
                'group_name' => 'contract'
            ],
            [
                'name' => 'create_invoice',
                'type_id' => $admin,
                'group_name' => 'invoice'
            ],
            [
                'name' => 'view_invoice',
                'type_id' => $admin,
                'group_name' => 'invoice'
            ],
            [
                'name' => 'edit_invoice',
                'type_id' => $admin,
                'group_name' => 'invoice'
            ],
            [
                'name' => 'delete_invoice',
                'type_id' => $admin,
                'group_name' => 'invoice'
            ],
            [
                'name' => 'create_customer',
                'type_id' => $admin,
                'group_name' => 'customer'
            ],
            [
                'name' => 'view_customer',
                'type_id' => $admin,
                'group_name' => 'customer'
            ],
            [
                'name' => 'edit_customer',
                'type_id' => $admin,
                'group_name' => 'customer'
            ],
            [
                'name' => 'delete_customer',
                'type_id' => $admin,
                'group_name' => 'customer'
            ],
            [
                'name' => 'create_product',
                'type_id' => $admin,
                'group_name' => 'product'
            ],
            [
                'name' => 'view_product',
                'type_id' => $admin,
                'group_name' => 'product'
            ],
            [
                'name' => 'edit_product',
                'type_id' => $admin,
                'group_name' => 'product'
            ],
            [
                'name' => 'delete_product',
                'type_id' => $admin,
                'group_name' => 'product'
            ],
            [
                'name' => 'create_addon',
                'type_id' => $admin,
                'group_name' => 'addon'
            ],
            [
                'name' => 'view_addon',
                'type_id' => $admin,
                'group_name' => 'addon'
            ],
            [
                'name' => 'edit_addon',
                'type_id' => $admin,
                'group_name' => 'addon'
            ],
            [
                'name' => 'delete_addon',
                'type_id' => $admin,
                'group_name' => 'addon'
            ],
            [
                'name' => 'create_country',
                'type_id' => $admin,
                'group_name' => 'country'
            ],
            [
                'name' => 'view_country',
                'type_id' => $admin,
                'group_name' => 'country'
            ],
            [
                'name' => 'edit_country',
                'type_id' => $admin,
                'group_name' => 'country'
            ],
            [
                'name' => 'delete_country',
                'type_id' => $admin,
                'group_name' => 'country'
            ],
            [
                'name' => 'create_city',
                'type_id' => $admin,
                'group_name' => 'city'
            ],
            [
                'name' => 'view_city',
                'type_id' => $admin,
                'group_name' => 'city'
            ],
            [
                'name' => 'edit_city',
                'type_id' => $admin,
                'group_name' => 'city'
            ],
            [
                'name' => 'delete_city',
                'type_id' => $admin,
                'group_name' => 'city'
            ],
            [
                'name' => 'create_location',
                'type_id' => $admin,
                'group_name' => 'location'
            ],
            [
                'name' => 'view_location',
                'type_id' => $admin,
                'group_name' => 'location'
            ],
            [
                'name' => 'edit_location',
                'type_id' => $admin,
                'group_name' => 'location'
            ],
            [
                'name' => 'delete_location',
                'type_id' => $admin,
                'group_name' => 'location'
            ],
            [
                'name' => 'create_coupon',
                'type_id' => $admin,
                'group_name' => 'coupon'
            ],
            [
                'name' => 'view_coupon',
                'type_id' => $admin,
                'group_name' => 'coupon'
            ],
            [
                'name' => 'edit_coupon',
                'type_id' => $admin,
                'group_name' => 'coupon'
            ],
            [
                'name' => 'delete_coupon',
                'type_id' => $admin,
                'group_name' => 'coupon'
            ],
            [
                'name' => 'create_insurance',
                'type_id' => $admin,
                'group_name' => 'insurance'
            ],
            [
                'name' => 'view_insurance',
                'type_id' => $admin,
                'group_name' => 'insurance'
            ],
            [
                'name' => 'edit_insurance',
                'type_id' => $admin,
                'group_name' => 'insurance'
            ],
            [
                'name' => 'delete_insurance',
                'type_id' => $admin,
                'group_name' => 'insurance'
            ],
            [
                'name' => 'create_blog',
                'type_id' => $admin,
                'group_name' => 'blog'
            ],
            [
                'name' => 'view_blog',
                'type_id' => $admin,
                'group_name' => 'blog'
            ],
            [
                'name' => 'edit_blog',
                'type_id' => $admin,
                'group_name' => 'blog'
            ],
            [
                'name' => 'delete_blog',
                'type_id' => $admin,
                'group_name' => 'blog'
            ],
            [
                'name' => 'create_term_length',
                'type_id' => $admin,
                'group_name' => 'term_length'
            ],
            [
                'name' => 'view_term_length',
                'type_id' => $admin,
                'group_name' => 'term_length'
            ],
            [
                'name' => 'edit_term_length',
                'type_id' => $admin,
                'group_name' => 'term_length'
            ],
            [
                'name' => 'delete_term_length',
                'type_id' => $admin,
                'group_name' => 'term_length'
            ],
            [
                'name' => 'create_move_request',
                'type_id' => $admin,
                'group_name' => 'move_request'
            ],
            [
                'name' => 'view_move_request',
                'type_id' => $admin,
                'group_name' => 'move_request'
            ],
            [
                'name' => 'edit_move_request',
                'type_id' => $admin,
                'group_name' => 'move_request'
            ],
            [
                'name' => 'delete_move_request',
                'type_id' => $admin,
                'group_name' => 'move_request'
            ],
            [
                'name' => 'create_move_in',
                'type_id' => $admin,
                'group_name' => 'move_in'
            ],
            [
                'name' => 'view_move_in',
                'type_id' => $admin,
                'group_name' => 'move_in'
            ],
            [
                'name' => 'edit_move_in',
                'type_id' => $admin,
                'group_name' => 'move_in'
            ],
            [
                'name' => 'delete_move_in',
                'type_id' => $admin,
                'group_name' => 'move_in'
            ],
            [
                'name' => 'create_move_out',
                'type_id' => $admin,
                'group_name' => 'move_out'
            ],
            [
                'name' => 'view_move_out',
                'type_id' => $admin,
                'group_name' => 'move_out'
            ],
            [
                'name' => 'edit_move_out',
                'type_id' => $admin,
                'group_name' => 'move_out'
            ],
            [
                'name' => 'delete_move_out',
                'type_id' => $admin,
                'group_name' => 'move_out'
            ],
            [
                'name' => 'create_warehouse',
                'type_id' => $admin,
                'group_name' => 'warehouse'
            ],
            [
                'name' => 'view_warehouse',
                'type_id' => $admin,
                'group_name' => 'warehouse'
            ],
            [
                'name' => 'edit_warehouse',
                'type_id' => $admin,
                'group_name' => 'warehouse'
            ],
            [
                'name' => 'delete_warehouse',
                'type_id' => $admin,
                'group_name' => 'warehouse'
            ],
            [
                'name' => 'create_storage_level',
                'type_id' => $admin,
                'group_name' => 'storage_level'
            ],
            [
                'name' => 'view_storage_level',
                'type_id' => $admin,
                'group_name' => 'storage_level'
            ],
            [
                'name' => 'edit_storage_level',
                'type_id' => $admin,
                'group_name' => 'storage_level'
            ],
            [
                'name' => 'delete_storage_level',
                'type_id' => $admin,
                'group_name' => 'storage_level'
            ],
            [
                'name' => 'create_storage_type',
                'type_id' => $admin,
                'group_name' => 'storage_type'
            ],
            [
                'name' => 'view_storage_type',
                'type_id' => $admin,
                'group_name' => 'storage_type'
            ],
            [
                'name' => 'edit_storage_type',
                'type_id' => $admin,
                'group_name' => 'storage_type'
            ],
            [
                'name' => 'delete_storage_type',
                'type_id' => $admin,
                'group_name' => 'storage_type'
            ],
            [
                'name' => 'create_storage_size',
                'type_id' => $admin,
                'group_name' => 'storage_size'
            ],
            [
                'name' => 'view_storage_size',
                'type_id' => $admin,
                'group_name' => 'storage_size'
            ],
            [
                'name' => 'edit_storage_size',
                'type_id' => $admin,
                'group_name' => 'storage_size'
            ],
            [
                'name' => 'delete_storage_size',
                'type_id' => $admin,
                'group_name' => 'storage_size'
            ],
            [
                'name' => 'create_storage_size',
                'type_id' => $admin,
                'group_name' => 'storage_size'
            ],
            [
                'name' => 'view_storage_size',
                'type_id' => $admin,
                'group_name' => 'storage_size'
            ],
            [
                'name' => 'edit_storage_size',
                'type_id' => $admin,
                'group_name' => 'storage_size'
            ],
            [
                'name' => 'delete_storage_size',
                'type_id' => $admin,
                'group_name' => 'storage_size'
            ],
            [
                'name' => 'create_storage_unit',
                'type_id' => $admin,
                'group_name' => 'storage_unit'
            ],
            [
                'name' => 'view_storage_unit',
                'type_id' => $admin,
                'group_name' => 'storage_unit'
            ],
            [
                'name' => 'edit_storage_unit',
                'type_id' => $admin,
                'group_name' => 'storage_unit'
            ],
            [
                'name' => 'delete_storage_unit',
                'type_id' => $admin,
                'group_name' => 'storage_unit'
            ],
            [
                'name' => 'view_app_settings',
                'type_id' => $admin,
                'group_name' => 'app_settings'
            ],
            [
                'name' => 'edit_app_settings',
                'type_id' => $admin,
                'group_name' => 'app_settings'
            ],
            [
                'name' => 'create_measurement_unit',
                'type_id' => $admin,
                'group_name' => 'measurement_unit'
            ],
            [
                'name' => 'view_measurement_unit',
                'type_id' => $admin,
                'group_name' => 'measurement_unit'
            ],
            [
                'name' => 'edit_measurement_unit',
                'type_id' => $admin,
                'group_name' => 'measurement_unit'
            ],
            [
                'name' => 'delete_measurement_unit',
                'type_id' => $admin,
                'group_name' => 'measurement_unit'
            ],
            [
                'name' => 'create_notification_template',
                'type_id' => $admin,
                'group_name' => 'notification_template'
            ],
            [
                'name' => 'view_notification_template',
                'type_id' => $admin,
                'group_name' => 'notification_template'
            ],
            [
                'name' => 'edit_notification_template',
                'type_id' => $admin,
                'group_name' => 'notification_template'
            ],
            [
                'name' => 'delete_notification_template',
                'type_id' => $admin,
                'group_name' => 'notification_template'
            ],
            [
                'name' => 'create_contract_template',
                'type_id' => $admin,
                'group_name' => 'contract_template'
            ],
            [
                'name' => 'view_contract_template',
                'type_id' => $admin,
                'group_name' => 'contract_template'
            ],
            [
                'name' => 'edit_contract_template',
                'type_id' => $admin,
                'group_name' => 'contract_template'
            ],
            [
                'name' => 'delete_contract_template',
                'type_id' => $admin,
                'group_name' => 'contract_template'
            ],
            [
                'name' => 'create_require_document',
                'type_id' => $admin,
                'group_name' => 'require_document'
            ],
            [
                'name' => 'view_require_document',
                'type_id' => $admin,
                'group_name' => 'require_document'
            ],
            [
                'name' => 'edit_require_document',
                'type_id' => $admin,
                'group_name' => 'require_document'
            ],
            [
                'name' => 'delete_require_document',
                'type_id' => $admin,
                'group_name' => 'require_document'
            ],
            [
                'name' => 'create_lead_status',
                'type_id' => $admin,
                'group_name' => 'lead_status'
            ],
            [
                'name' => 'view_lead_status',
                'type_id' => $admin,
                'group_name' => 'lead_status'
            ],
            [
                'name' => 'edit_lead_status',
                'type_id' => $admin,
                'group_name' => 'lead_status'
            ],
            [
                'name' => 'delete_lead_status',
                'type_id' => $admin,
                'group_name' => 'lead_status'
            ],
            [
                'name' => 'create_lead_source',
                'type_id' => $admin,
                'group_name' => 'lead_source'
            ],
            [
                'name' => 'view_lead_source',
                'type_id' => $admin,
                'group_name' => 'lead_source'
            ],
            [
                'name' => 'edit_lead_source',
                'type_id' => $admin,
                'group_name' => 'lead_source'
            ],
            [
                'name' => 'delete_lead_source',
                'type_id' => $admin,
                'group_name' => 'lead_source'
            ],

        ];
    }
}
