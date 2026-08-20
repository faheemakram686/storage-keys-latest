<?php

namespace App\Services\Contact;

use App\Models\Contact;
use App\Models\ContactRole;

class ContactRoleSyncService
{
    public const OWNER = 'owner';
    public const BILLING = 'billing';
    public const VIEWER = 'viewer';

    /**
     * Assign a contact role and keep legacy contact_type in sync.
     * owner ↔ primary, billing|viewer ↔ general
     */
    public function assignRole(Contact $contact, string $alias, bool $demoteOtherOwners = true): Contact
    {
        $role = ContactRole::findByAlias($alias);

        if (!$role) {
            return $contact;
        }

        if ($alias === self::OWNER && $demoteOtherOwners) {
            $viewer = ContactRole::findByAlias(self::VIEWER);
            Contact::query()
                ->where('customer_id', $contact->customer_id)
                ->where('id', '!=', $contact->id)
                ->where('is_deleted', 0)
                ->where(function ($q) use ($role) {
                    $q->where('contact_role_id', $role->id)
                        ->orWhere('contact_type', 'primary');
                })
                ->get()
                ->each(function (Contact $other) use ($viewer) {
                    $other->contact_role_id = $viewer ? $viewer->id : null;
                    $other->contact_type = 'general';
                    $other->save();
                });
        }

        $contact->contact_role_id = $role->id;
        $contact->contact_type = $alias === self::OWNER ? 'primary' : 'general';
        $contact->save();

        return $contact->fresh(['contactRole.permissions']);
    }

    public function aliasFromContactType(?string $contactType): string
    {
        if (strtolower((string) $contactType) === 'primary') {
            return self::OWNER;
        }

        return self::VIEWER;
    }

    public function syncTypeFromRole(Contact $contact): void
    {
        $alias = optional($contact->contactRole)->alias
            ?: $this->aliasFromContactType($contact->getRawOriginal('contact_type') ?? $contact->contact_type);

        $contact->contact_type = $alias === self::OWNER ? 'primary' : 'general';
    }

    public function contactHasPermission(Contact $contact, string $permission): bool
    {
        $contact->loadMissing('contactRole.permissions');

        if (!$contact->contactRole) {
            // Legacy fallback: primary ≈ owner
            $type = $contact->getAttributes()['contact_type'] ?? null;
            if (strtolower((string) $type) === 'primary') {
                return in_array($permission, [
                    'view_portal', 'view_invoices', 'pay_invoices', 'view_contracts',
                    'manage_contacts', 'update_account',
                ], true);
            }

            return in_array($permission, ['view_portal', 'view_invoices', 'view_contracts'], true);
        }

        return $contact->contactRole->permissions->contains('name', $permission);
    }

    public function contactHasAnyPermission(Contact $contact, array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->contactHasPermission($contact, $permission)) {
                return true;
            }
        }

        return false;
    }
}
