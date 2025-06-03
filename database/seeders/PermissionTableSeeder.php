<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        Schema::disableForeignKeyConstraints();
//        DB::table('permissions')->truncate();
//        Schema::enableForeignKeyConstraints();

        $permissions = [
            // user
            ['group' => 'users', 'name' => 'view_users', 'title' => 'View users', 'guard_name' => 'web'],
            ['group' => 'users', 'name' => 'add_user', 'title' => 'Add user', 'guard_name' => 'web'],
            ['group' => 'users', 'name' => 'edit_user', 'title' => 'Edit user', 'guard_name' => 'web'],
            ['group' => 'users', 'name' => 'delete_user', 'title' => 'Delete user', 'guard_name' => 'web'],

            // roles
            ['group' => 'roles', 'name' => 'view_roles', 'title' => 'View roles', 'guard_name' => 'web'],
            // ['group' => 'roles', 'name' => 'add_role', 'title' => 'Add role', 'guard_name' => 'web'],
            ['group' => 'roles', 'name' => 'edit_role', 'title' => 'Edit role', 'guard_name' => 'web'],
            // ['group' => 'roles', 'name' => 'delete_role', 'title' => 'Delete role', 'guard_name' => 'web'],

            // spaces
            ['group' => 'spaces', 'name' => 'view_spaces', 'title' => 'View spaces', 'guard_name' => 'web'],
            ['group' => 'spaces', 'name' => 'add_space', 'title' => 'Add space', 'guard_name' => 'web'],
            ['group' => 'spaces', 'name' => 'edit_space', 'title' => 'Edit space', 'guard_name' => 'web'],
            ['group' => 'spaces', 'name' => 'delete_space', 'title' => 'Delete space', 'guard_name' => 'web'],

            // locations
            ['group' => 'locations', 'name' => 'view_locations', 'title' => 'View locations', 'guard_name' => 'web'],
            ['group' => 'locations', 'name' => 'add_location', 'title' => 'Add location', 'guard_name' => 'web'],
            ['group' => 'locations', 'name' => 'edit_location', 'title' => 'Edit location', 'guard_name' => 'web'],
            ['group' => 'locations', 'name' => 'delete_location', 'title' => 'Delete location', 'guard_name' => 'web'],

            // size_vartients
            ['group' => 'size_vartients', 'name' => 'view_size_vartients', 'title' => 'View size vartients', 'guard_name' => 'web'],
            ['group' => 'size_vartients', 'name' => 'add_size_vartient', 'title' => 'Add size vartient', 'guard_name' => 'web'],
            ['group' => 'size_vartients', 'name' => 'edit_size_vartient', 'title' => 'Edit size vartient', 'guard_name' => 'web'],
            ['group' => 'size_vartients', 'name' => 'delete_size_vartient', 'title' => 'Delete size vartient', 'guard_name' => 'web'],

            // addons
            ['group' => 'addons', 'name' => 'view_addons', 'title' => 'View addons', 'guard_name' => 'web'],
            ['group' => 'addons', 'name' => 'add_addon', 'title' => 'Add addon', 'guard_name' => 'web'],
            ['group' => 'addons', 'name' => 'edit_addon', 'title' => 'Edit addon', 'guard_name' => 'web'],
            ['group' => 'addons', 'name' => 'delete_addon', 'title' => 'Delete addon', 'guard_name' => 'web'],

            // bookings
            ['group' => 'bookings', 'name' => 'view_bookings', 'title' => 'View bookings', 'guard_name' => 'web'],
            ['group' => 'bookings', 'name' => 'approve_booking', 'title' => 'Approve booking', 'guard_name' => 'web'],
            ['group' => 'bookings', 'name' => 'cancel_booking', 'title' => 'Cancel booking', 'guard_name' => 'web'],
            ['group' => 'bookings', 'name' => 'delete_booking', 'title' => 'Delete booking', 'guard_name' => 'web'],

            // payments
            ['group' => 'payments', 'name' => 'view_payments', 'title' => 'View payments', 'guard_name' => 'web'],
            ['group' => 'payments', 'name' => 'approve_payment', 'title' => 'Approve payment', 'guard_name' => 'web'],
            ['group' => 'payments', 'name' => 'cancel_payment', 'title' => 'Cancel payment', 'guard_name' => 'web'],
            ['group' => 'payments', 'name' => 'delete_payment', 'title' => 'Delete payment', 'guard_name' => 'web'],

            // blogs
            ['group' => 'blogs', 'name' => 'view_blogs', 'title' => 'View blogs', 'guard_name' => 'web'],
            ['group' => 'blogs', 'name' => 'add_blog', 'title' => 'Add blog', 'guard_name' => 'web'],
            ['group' => 'blogs', 'name' => 'edit_blog', 'title' => 'Edit blog', 'guard_name' => 'web'],
            ['group' => 'blogs', 'name' => 'delete_blog', 'title' => 'Delete blog', 'guard_name' => 'web'],

            // sliders
            ['group' => 'sliders', 'name' => 'view_sliders', 'title' => 'View sliders', 'guard_name' => 'web'],
            ['group' => 'sliders', 'name' => 'edit_slider', 'title' => 'Edit slider', 'guard_name' => 'web'],

            // site_settings
            ['group' => 'site_settings', 'name' => 'view_site_settings', 'title' => 'View site settings', 'guard_name' => 'web'],
            ['group' => 'site_settings', 'name' => 'edit_site_settings', 'title' => 'Edit site settings', 'guard_name' => 'web'],

        ];

        Permission::insert($permissions);

    }
}