<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    use HasFactory;
    protected $fillable = [
        'name', 'is_admin', 'is_default', 'type_id', 'created_by', 'alias'
    ];

    protected static $logAttributes = [
        'name', 'is_admin', 'createdBy.name', 'type.name'
    ];
}
