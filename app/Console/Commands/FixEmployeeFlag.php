<?php
namespace App\Console\Commands;

use App\Models\Core\Auth\User;
use Illuminate\Console\Command;

class FixEmployeeFlag extends Command
{
    protected $signature   = 'user:fix-employee-flag {id}';
    protected $description = 'Set is_in_employee=1 so user appears in All Employees';

    public function handle(): int
    {
        $id   = $this->argument('id');
        $user = User::find($id);
        if (!$user) { $this->error("User $id not found"); return 1; }
        $before = $user->is_in_employee;
        $user->is_in_employee = 1;
        $user->save();
        $user->refresh();
        $after = $user->is_in_employee;
        $this->info("Name  : {$user->first_name} {$user->last_name}");
        $this->info("Email : {$user->email}");
        $this->info("BEFORE is_in_employee: $before");
        $this->info("AFTER  is_in_employee: $after");
        if ($after == 1) { $this->info('SUCCESS - User will appear in All Employees'); return 0; }
        $this->error('FAILED'); return 1;
    }
}
