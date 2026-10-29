<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAccountDeletion extends Model
{
    protected $table = 'customer_account_deletions';

    protected $fillable = [
        'customer_id', 'contact_id', 'email', 'reason', 'comments', 'ip_address', 'user_agent'
    ];

    // Reasons the customer app can offer; "other" requires comments.
    public const REASONS = [
        'no_longer_need_storage' => 'I no longer need storage',
        'moving_away'            => 'I am moving away',
        'found_alternative'      => 'I found another storage provider',
        'too_expensive'          => 'It is too expensive',
        'poor_service'           => 'I am not happy with the service',
        'app_issues'             => 'I had problems using the app',
        'privacy_concerns'       => 'Privacy concerns',
        'other'                  => 'Other',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id', 'id');
    }
}
