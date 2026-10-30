<?php

namespace App\Models;

use App\Models\Core\Auth\User;
use Illuminate\Database\Eloquent\Model;

class EmployeeAccountDeletion extends Model
{
    protected $table = 'employee_account_deletions';

    protected $fillable = [
        'user_id', 'email', 'reason', 'comments', 'ip_address', 'user_agent'
    ];

    // Reasons the employee app can offer; "other" requires comments.
    public const REASONS = [
        'left_company'      => 'I no longer work here',
        'not_using_app'     => 'I no longer use the app',
        'duplicate_account' => 'I have another account',
        'app_issues'        => 'I had problems using the app',
        'privacy_concerns'  => 'Privacy concerns',
        'other'             => 'Other',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }
}
