<?php

namespace App\Http\Controllers\Core\Auth\Role;

use App\Filters\Common\Auth\RoleFilter as AppRoleFilter;
use App\Filters\Core\RoleFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Core\Auth\Role\RoleRequest;
use App\Models\Core\Auth\Role;
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
        return (new AppRoleFilter(
            $this->service
                ->with('users:id,first_name,last_name,email', 'users.profilePicture')
                ->orderBy('id')
                ->filters($this->filter)
        ))->filter()
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
       $request['alias']=$request->name;
       $request['type_id']=3;
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

        $role = Role::find($request->id);
        $role->id = $request->id;
        $role->name = $request->name;
        $role->alias = $request->name;
        $role->type_id = 3;
        $permissionss=[];
        foreach ($request->permissions as $key => $permission){
            $arr[$key] = ["permission_id" => $key,];

        }


        $this->service
            ->setModel($role)
            ->beforeUpdated()
            ->update();
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
