<?php
namespace App\Repo;

use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\Type;
use App\Repo\Interfaces\RoleInterface;
use App\Services\Core\Auth\UserRoleSyncService;

class RoleClass implements RoleInterface {

    public function saveRole($request)
    {
        $admin = Type::findByAlias('admin')->id;
            $sy = new Role();
            $sy->name = $request->name;
            $sy->alias = $request->name;
            $sy->type_id = $admin;
            $sy->is_default = 1;
            $sy->save();
            return response()->json(['success' => 'Record save successfully'], 200);

    }

    /**
     * Portal (admin-type) roles only — never mix HRM/tenant roles into Portal UIs.
     */
    public function getRole()
    {
        return resolve(UserRoleSyncService::class)->rolesForType(UserRoleSyncService::TYPE_ADMIN);
    }

    /**
     * HRM (tenant-type) roles only.
     */
    public function getHrmRoles()
    {
        return resolve(UserRoleSyncService::class)->rolesForType(UserRoleSyncService::TYPE_TENANT);
    }

    /**
     * @param  string|null  $typeAlias  admin|tenant|app|null (all)
     */
    public function getRolesByType(?string $typeAlias = null)
    {
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

        if (optional($role->type)->alias === 'tenant' && $role->is_default) {
            return response()->json(['errors' => 'Default HRM role cannot be deleted'], 403);
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
