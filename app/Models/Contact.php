<?php

namespace App\Models;

use App\Services\Contact\ContactRoleSyncService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Contact extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'contacts';

    protected $guard = 'contact';

    protected $fillable = [
        'customer_id', 'first_name', 'last_name', 'email', 'password', 'status',
        'contact_type', 'contact_role_id', 'position', 'phone',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:00',
    ];

    protected $appends = ['role_alias'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function contactRole()
    {
        return $this->belongsTo(ContactRole::class, 'contact_role_id');
    }

    public function getRoleAliasAttribute(): ?string
    {
        return optional($this->contactRole)->alias;
    }

    public function hasContactPermission(string $permission): bool
    {
        return resolve(ContactRoleSyncService::class)->contactHasPermission($this, $permission);
    }

    public function hasAnyContactPermission(array $permissions): bool
    {
        return resolve(ContactRoleSyncService::class)->contactHasAnyPermission($this, $permissions);
    }

    public function isOwner(): bool
    {
        return optional($this->contactRole)->alias === ContactRoleSyncService::OWNER
            || strtolower((string) ($this->getAttributes()['contact_type'] ?? '')) === 'primary';
    }

    public function setStatusAttribute($value)
    {
        if ($value == 0) {
            $value = 0;
        }
        if ($value == 1) {
            $value = 1;
        }
        $this->attributes['status'] = $value;
    }

    public function getStatusAttribute($value)
    {
        if ($value == 1) {
            return 'Active';
        }
        if ($value == 0) {
            return 'In-Active';
        }

        return $value;
    }
}
