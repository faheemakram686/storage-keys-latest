<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactRole extends Model
{
    protected $fillable = ['name', 'alias', 'description'];

    public function permissions()
    {
        return $this->belongsToMany(
            ContactPermission::class,
            'contact_role_permission',
            'contact_role_id',
            'contact_permission_id'
        );
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'contact_role_id');
    }

    public static function findByAlias(string $alias): ?self
    {
        return static::query()->where('alias', $alias)->first();
    }

    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('name', $permission)->exists();
    }
}
