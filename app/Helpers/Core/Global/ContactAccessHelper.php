<?php

use App\Models\Contact;
use App\Services\Contact\ContactRoleSyncService;
use Illuminate\Support\Facades\Auth;

if (!function_exists('contact_has_permission')) {
    function contact_has_permission(string $permission, ?Contact $contact = null): bool
    {
        $contact = $contact ?: Auth::guard('contact')->user();

        if (!$contact instanceof Contact) {
            return false;
        }

        return resolve(ContactRoleSyncService::class)->contactHasPermission($contact, $permission);
    }
}

if (!function_exists('contact_has_any_permission')) {
    function contact_has_any_permission(array $permissions, ?Contact $contact = null): bool
    {
        $contact = $contact ?: Auth::guard('contact')->user();

        if (!$contact instanceof Contact) {
            return false;
        }

        return resolve(ContactRoleSyncService::class)->contactHasAnyPermission($contact, $permissions);
    }
}
