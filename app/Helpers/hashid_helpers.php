<?php

use App\Helpers\Hashid;

if (! function_exists('hashid_encode')) {
    function hashid_encode(int $id): string
    {
        return Hashid::encode($id);
    }
}

if (! function_exists('hashid_decode')) {
    function hashid_decode(string $value): ?int
    {
        return Hashid::decode($value);
    }
}
