<?php

namespace Database\Seeders\Auth;

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserTableSeeder.
 */
class UserTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();
        User::query()->truncate();

        // Add the master administrator, user id of 1
        User::query()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'avatar' => 'no_avatar.png',
            'email' => 'admin@demo.com',
            'phone' => '0300000000',
            'address' => 'test Address',
            'user_type' => 1,
            'status' => 0,
            'password' => Hash::make('12345678'),
            'status_id' => Status::findByNameAndType('status_active')->id,
            'is_in_employee' => 1,
        ]);

        $this->enableForeignKeys();
    }
}
