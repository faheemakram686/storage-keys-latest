<?php
namespace App\Repo;

use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\Type;
use App\Repo\Interfaces\RoleInterface;
use App\Services\Core\Auth\UserRoleSyncService;

class RoleClass implements RoleInterface {

    public function saveRole($request)
    {
        $tenant = Type::findByAlias('tenant')->id;
        $sy = new Role();
        $sy->name = $request->name;
        $sy->alias = $request->alias ?: $request->name;
        $sy->type_id = $tenant;
        $sy->is_default = 0;
        $sy->is_admin = 0;
        $sy->save();

        return response()->json(['success' => 'Record save successfully'], 200);
    }

    /**
     * Staff roles (unified CRM + HRM catalog).
     */
    public function getRole()
    {
        return resolve(UserRoleSyncService::class)->staffRoles();
    }

    /**
     * @deprecated Alias of getRole() — staff catalog is unified.
     */
    public function getHrmRoles()
    {
        return resolve(UserRoleSyncService::class)->staffRoles();
    }

    /**
     * @param  string|null  $typeAlias  admin|tenant|app|null (all)
     */
    public function getRolesByType(?string $typeAlias = null)
    {
        if ($typeAlias && in_array($typeAlias, ['admin', 'tenant'], true)) {
            return resolve(UserRoleSyncService::class)->staffRoles();
        }

        if ($typeAlias) {
            return resolve(UserRoleSyncService::class)->rolesForType($typeAlias);
        }

        return Role::query()->with('type')->orderBy('id', 'DESC')->get();
    }

    public function deleteRole($id)
    {
        $role = Role::with('type')->findOrFail($id);

        if ($role->isAdmin() || optional($role->type)->alias === 'app') {
            return response()->json(['errors' => 'Protected role cannot be deleted'], 403);
        }

        if ($role->is_default && in_array($role->alias, ['employee', 'department_manager', 'manager'], true)) {
            return response()->json(['errors' => 'Default staff role cannot be deleted'], 403);
        }

        $role->delete();

        return 1;
    }

    public function editRole($id)
    {
        // TODO: Implement editRole() method.
    }

    public function updateRole($request)
    {
        // TODO: Implement updateRole() method.
    }
}
