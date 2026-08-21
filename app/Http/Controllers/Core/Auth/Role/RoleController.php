<?php

namespace App\Http\Controllers\Core\Auth\Role;

use App\Filters\Common\Auth\RoleFilter as AppRoleFilter;
use App\Filters\Core\RoleFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Core\Auth\Role\RoleRequest;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\Type;
use App\Notifications\Core\Role\RoleNotification;
use App\Services\Core\Auth\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    public function __construct(RoleService $roleService, RoleFilter $filter)
    {
        $this->service = $roleService;
        $this->filter = $filter;
    }


    public function index()
    {
        $appTypeId = optional(Type::findByAlias('app'))->id;

        $query = $this->service
            ->with('users:id,first_name,last_name,email', 'users.profilePicture')
            ->orderBy('id')
            ->filters($this->filter);

        if ($appTypeId) {
            $query->where('type_id', '!=', $appTypeId);
        }

        $query->where(function ($q) {
            $q->where('is_admin', 0)->orWhereNull('is_admin');
        });

        return (new AppRoleFilter($query))->filter()
            ->paginate(request()->get('per_page', 10));
    }


    public function store(RoleRequest $request)
    {
        $this->service
            ->beforeCreated()
            ->save(request()->except('is_default', 'is_admin'));

        $this->service
            ->notify('roles_created')
            ->when(count($request->get('permissions', [])), function (RoleService $service) use ($request) {
                $service->assignPermissions($request->get('permissions'));
            });

        return created_responses('role');
    }

    public function storesk(Request $request)
    {
        $tenantTypeId = Type::findByAlias('tenant')->id;
        $request->merge([
            'alias' => $request->name,
            'type_id' => $tenantTypeId,
            'is_admin' => 0,
            'is_default' => 0,
        ]);

        $this->service
            ->beforeCreated()
            ->save(request()->except('is_default', 'is_admin'));

        $this->service
            ->notify('roles_created')
            ->when(count($request->get('permissions', [])), function (RoleService $service) use ($request) {
                $service->assignPermissions($request->get('permissions'));
            });

        return redirect()->route('roles.index')->withSuccess(['Record Saved successfully']);
    }
    public function show(Role $role)
    {
        $role = $role->load('permissions')->toArray();
        if (request()->group_permission) {
            $role['permissions'] = collect($role['permissions'])->groupBy(function ($permission) {
                return $permission['group_name'];
            });
        }
        return $role;
    }


    public function edit(RoleRequest $request, Role $role)
    {
        if ($role->isAdmin()) {
            return redirect()->back()->withFlashDanger(__t('action_not_allowed'));
        }
        return view('core.auth.role.edit')
            ->withRole($role)
            ->withRolePermissions($role->permissions->pluck('name')->all());
    }
    public function editsk($id)
    {
        $role = Role::find($id);
        if ($role->isAdmin()) {
            return redirect()->back()->withSuccess(['You are not allowed to perform this task']);
        }
        return view('backend.roles.edit')
            ->withRole($role)
            ->withRolePermissions($role->permissions->pluck('name')->all());
    }

    public function update(Role $role, RoleRequest $request)
    {
        $this->service
            ->setModel($role)
            ->beforeUpdated()
            ->update();

        return updated_responses('role');
    }

    public function updatesk(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:roles,id',
            'name' => 'required|regex:/^[A-Za-z 0-9_]+$/|unique:roles,name,' . $request->id . ',id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $role = Role::with('users')->findOrFail($request->id);

        // App Admin must not be changed from the backend roles UI.
        if ($role->isAdmin()) {
            return redirect()
                ->route('roles.index')
                ->withErrors(['You are not allowed to update the App Admin role.']);
        }

        $role->name = $request->name;
        $role->save();

        // Blade form posts permission IDs as permissions[]; normalize either format.
        $permissionIds = collect($request->input('permissions', []))
            ->map(function ($permission) {
                if (is_array($permission)) {
                    return (int) ($permission['permission_id'] ?? $permission['id'] ?? 0);
                }

                return (int) $permission;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        $role->permissions()->sync($permissionIds);

        // Clear cached permissions for users assigned to this role.
        foreach ($role->users as $user) {
            cache()->forget('user-' . $user->id);
            cache()->forget('user-roles-permissions-' . $user->id);
            cache()->forget('user-roles-' . $user->id);
            cache()->forget('auth-user-permissions-' . $user->id);
        }

        return redirect()->route('roles.index')->withSuccess(['Record Updated successfully']);
    }

    public function destroy(Role $role, RoleRequest $request)
    {
        $this->service
            ->setModel($role)
            ->whileDeleting()
            ->delete();

        notify()
            ->on('roles_deleted')
            ->with((object)$role->toArray())
            ->send(RoleNotification::class);

        return deleted_responses('role');
    }
    public function destroysk($id)
    {



//        return redirect()->route('roles.index')->withSuccess(['Record Deleted successfully']);

    }
}
