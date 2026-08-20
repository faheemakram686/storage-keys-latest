<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\User;
use App\Models\Tenant\Employee\Department;
use App\Models\Tenant\Employee\Designation;
use App\Models\Tenant\Employee\EmploymentStatus;
use App\Repo\Interfaces\UserInterface;
use App\Repo\RoleClass;

use App\Services\Core\Auth\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $user;
    private  $role;

    public function __construct(UserInterface $user,UserService $service)
    {
        $this->role = new RoleClass();
        $this->user = $user;
        $this->service = $service;
    }
   public function index()
    {
         $data['portal_roles'] = $this->role->getRole();
         $data['hrm_roles'] = $this->role->getHrmRoles();
         // Backward-compat for any view still expecting $data['role']
         $data['role'] = $data['portal_roles'];
         $data['departments'] = Department::query()->get(['id', 'name']);
         $data['designations'] = Designation::query()->get(['id', 'name']);
         $data['employment_statuses'] = EmploymentStatus::query()
             ->where(function ($query) {
                 $query->where('alias', '!=', 'terminated')->orWhereNull('alias');
             })
             ->get(['id', 'name', 'alias']);
         $data['employee_id'] = 'EMP-' . (User::count() + 1);

        return view('backend.users.index')->with(compact('data'));
    }
    public function getUser()
    {
        return $res=$this->user->getUser();
    }
    public function saveUser(Request $request)
    {
         $res=$this->user->saveUser($request);

        return $res;
    }
    public function deleteUser(Request $request)
    {
        $this->user->deleteUser($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);
    }
    public function editUser(Request $request)
    {
        $data['portal_roles'] = $this->role->getRole();
        $data['hrm_roles'] = $this->role->getHrmRoles();
        $data['role'] = $data['portal_roles'];
        $data['st'] = $this->user->editUser($request->id);

        $sync = resolve(\App\Services\Core\Auth\UserRoleSyncService::class);
        $user = $data['st'];
        $data['portal_role_id'] = optional($sync->getPortalRole($user))->id;
        $data['hrm_role_id'] = optional($sync->getHrmRole($user))->id;

        return $data;
    }
    public function updateUser(Request $request)
    {
        $res=$this->user->updateUser($request);
        return $res;
    }

}
