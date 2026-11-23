<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A StorageKeys facility (Al Quoz Dubai, Sharjah). Overrides the address, map link and coordinates in
 * App\Support\LocationPages::facilities() and fills {{address:key}} placeholders.
 */
class Office extends Model
{
    protected $fillable = ['key', 'name', 'street', 'locality', 'region', 'map_link', 'hours', 'phone', 'email', 'lat', 'lng', 'is_deleted'];

    protected $attributes = ['is_deleted' => false];

    public function addressLine(): string
    {
        return $this->street . ', ' . $this->locality . ', UAE';
    }
}
