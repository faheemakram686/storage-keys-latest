<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactPermission extends Model
{
    protected $fillable = ['name', 'group_name', 'description'];

    public function roles()
    {
        return $this->belongsToMany(
            ContactRole::class,
            'contact_role_permission',
            'contact_permission_id',
            'contact_role_id'
        );
    }
}
