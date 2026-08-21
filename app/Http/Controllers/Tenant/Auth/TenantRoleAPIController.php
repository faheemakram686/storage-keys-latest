<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Filters\Common\Auth\RoleFilter as AppRoleFilter;
use App\Filters\Core\RoleFilter;
use App\Http\Controllers\Controller;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\Type;
use Illuminate\Database\Eloquent\Builder;

class TenantRoleAPIController extends Controller
{
    public function __construct(RoleFilter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * Selectable staff roles (unified CRM + HRM), excluding App Admin and department_manager from default pickers.
     */
    public function index()
    {
        $appTypeId = optional(Type::findByAlias('app'))->id;

        return (new AppRoleFilter(
            Role::query()
                ->when($appTypeId, fn (Builder $q) => $q->where('type_id', '!=', $appTypeId))
                ->where(fn (Builder $q) => $q->where('is_admin', 0)->orWhereNull('is_admin'))
                ->when(optional(tenant())->id, function (Builder $builder) {
                    $builder->where(function (Builder $inner) {
                        $inner->where('tenant_id', optional(tenant())->id)
                            ->orWhereNull('tenant_id');
                    });
                })
                ->where(fn (Builder $b) => $b
                    ->where('alias', '!=', 'department_manager')
                    ->orWhereNull('alias'))
        ))->filter()
            ->filters($this->filter)
            ->get(['id', 'name', 'is_default', 'is_admin', 'alias']);
    }

    public function filterRoles()
    {
        $appTypeId = optional(Type::findByAlias('app'))->id;

        return (new AppRoleFilter(
            Role::query()
                ->when($appTypeId, fn (Builder $q) => $q->where('type_id', '!=', $appTypeId))
                ->where(fn (Builder $q) => $q->where('is_admin', 0)->orWhereNull('is_admin'))
                ->when(optional(tenant())->id, function (Builder $builder) {
                    $builder->where(function (Builder $inner) {
                        $inner->where('tenant_id', optional(tenant())->id)
                            ->orWhereNull('tenant_id');
                    });
                })
        ))->filter()
            ->filters($this->filter)
            ->get(['id', 'name', 'is_default', 'is_admin', 'alias']);
    }
}
