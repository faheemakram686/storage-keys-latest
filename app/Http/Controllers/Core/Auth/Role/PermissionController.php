<?php

namespace App\Http\Controllers\Core\Auth\Role;

use App\Filters\Common\Auth\PermissionFilter;
use App\Filters\Core\BaseFilter;
use App\Http\Controllers\Controller;
use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Type;
use Illuminate\Database\Eloquent\Collection;

class PermissionController extends Controller
{

    public function __construct(BaseFilter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * Display a listing of the resource.
     * Staff role editor gets CRM + HRM + common permissions (excludes app-only).
     *
     * @return Permission[]|Collection|array
     */
    public function index()
    {
        $appTypeId = optional(Type::findByAlias('app'))->id;
        $adminTypeId = optional(Type::findByAlias('admin'))->id;
        $tenantTypeId = optional(Type::findByAlias('tenant'))->id;

        $query = Permission::query()->filters($this->filter);

        // Unless explicitly filtered, exclude app-only permissions from staff editors.
        if (!request()->filled('type') && !request()->filled('type_id') && $appTypeId) {
            $query->where(function ($q) use ($appTypeId) {
                $q->whereNull('type_id')->orWhere('type_id', '!=', $appTypeId);
            });
        }

        return PermissionFilter::new(true, $query)
            ->filter()
            ->get()
            ->when(!request()->without_group, function (Collection $permissions) use ($adminTypeId, $tenantTypeId) {
                return $permissions->groupBy(function ($permission) use ($adminTypeId, $tenantTypeId) {
                    $section = 'Common';
                    if ($adminTypeId && (int) $permission->type_id === (int) $adminTypeId) {
                        $section = 'CRM';
                    } elseif ($tenantTypeId && (int) $permission->type_id === (int) $tenantTypeId) {
                        $section = 'HRM';
                    }

                    return $section . ': ' . ($permission->group_name ?: 'other');
                });
            });
    }

}
