<?php

namespace App\Http\Controllers\Backend;

use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\Type;
//use App\Models\Role;
use App\Models\Core\Auth\User;
use App\Repo\Interfaces\RoleInterface;
use App\Repo\RoleClass;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    private $role;
    public function __construct(RoleInterface $role) {
        $this->middleware('permission:view_roles', ['only' => ['index', 'show']]);
         $this->middleware('permission:add_role', ['only' => ['create','store']]);
        $this->middleware('permission:edit_role', ['only' => ['edit','update']]);
         $this->middleware('permission:delete_role', ['only' => ['destroy']]);
        $this->role = $role;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['user'] = User::where('status',1)->get();
        return view('backend.roles.indexx')->with(compact('data'));
    }

    public function getRoles()
    {
        $admin = Type::findByAlias('admin')->id;
       return $role_count = Role::with('users')->where('type_id',$admin)->get();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $admin = Type::findByAlias('admin')->id;
        $permissions = Permission::where('type_id',$admin)->get();

        $permission_groups = $this->formatPermissions($permissions);

        return view('backend.roles.create', compact('permission_groups'));
    }

    public function saveRole(Request $request)
    {

         $this->role->saveRole($request);
        return redirect()->route('roles.index')->withSuccess(['Record Saved successfully']);
    }

    public function editRole($id)
    {
        $admin = Type::findByAlias('admin')->id;
        $permissions = Permission::where('type_id',$admin)->get();
        $permission_groups = $this->formatPermissions($permissions);
        $role = \App\Models\Core\Auth\Role::with('permissions')->find($id);
        return view('backend.roles.edit', compact('role', 'permission_groups', 'permissions'));
    }

    public function assignRole(Request $request)
    {

        $role = Role::find($request->id);
        $user= User::find($request->user_id);
//        $userrole = $user->roles()->detach($role->id);
        $userrole = $user->assignRole($role);
        if($userrole)
        {
            return response()->json(['success' => 'Role Assigned successfully'], 200);
        }

    }
    public function deattachRole(Request $request)
    {
        $role = Role::find($request->role_id);
        $user= User::find($request->user_id_assigned);
        $userrole = $user->roles()->detach($role->id);
        if($userrole)
        {
            return response()->json(['success' => 'Role Deattached successfully'], 200);
        }

    }

    public function getAssignedUsers(Request $request)
    {
        $roleUsers = Role::with('users')->find($request->id);
        return $roleUsers;
    }
    public function updateRole(Request $request)
    {
        return $request->all();
        $this->role->deleteRole($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }
    public function deleteRole(Request $request)
    {
        $this->role->deleteRole($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[A-Za-z 0-9_]+$/|unique:roles',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                'message' => $validator->errors()->first(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }


//        try{
            $new_role = Role::create([
                'name' => strtolower($request->name)
            ]);
           return $new_role;

            if($request->has('permissions')){
                $new_role->permissions()->sync($request->permissions);
            }

//            return response()->json([
//                'success' => JsonResponse::HTTP_OK,
//                'message' => 'Role added successfully.',
//            ], JsonResponse::HTTP_OK);
//
//        }catch(Exception $e){
//            return response()->json([
//                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
//                'message' => $e->getMessage(),
//            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
//        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permissions = Permission::get();
        $permission_groups = $this->formatPermissions($permissions);
        $role = Role::with('permissions')->find($id);
        return view('backend.roles.edit', compact('role', 'permission_groups', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[A-Za-z 0-9_]+$/|unique:roles,name,'.$id.',id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                'message' => $validator->errors()->first(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{
            $role = Role::find($id);
            // $role->update([
            //     'name' => strtolower($request->name),
            //     'title' => ucfirst(strtolower($request->name)),
            // ]);

            $role->permissions()->sync($request->permissions);

            return response()->json([
                'success' => JsonResponse::HTTP_OK,
                'message' => 'Permissions updated successfully.',
            ], JsonResponse::HTTP_OK);

        }catch(Exception $e){
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            $role = Role::findOrFail($id);
            $role->delete();
            return response()->json([
                'success' => JsonResponse::HTTP_OK,
                'message' => 'Role Deleted successfully'
            ], JsonResponse::HTTP_OK);
        }
        catch (\Exception $exception)
        {
            return response()->json([
                'message' => $exception->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function dataTable () {
        $role = Role::query()->get();
        return Datatables::of($role)
            ->addColumn('actions', function ($record) {
                // $actions = '';
                // if(auth()->user()->hasPermissionTo('edit_role') || auth()->user()->hasPermissionTo('delete_role')) {
                    $actions =  '<div class="drodown">
                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">';
//                    if (auth()->user()->hasPermissionTo('edit_role')) {
                        $actions .= '<li>
                                        <a href="'. route('roles.edit', $record->id). '" data-toggle="tooltip" data-placement="top" title="Edit Role">
                                            <em class="icon ni ni-edit"></em><span>Edit</span>
                                        </a>
                                    </li>';
//                    }
                    // if (auth()->user()->hasPermissionTo('delete_role')) {
                    //     if($record->is_deleteable) {
                    //         $actions .= '<li>
                    //                         <a class="delete" href="javascript:void(0)" data-table="roles-table" data-method="get"
                    //                     data-url="' .route('roles.destroy', $record->id). '" data-toggle="tooltip" data-placement="top" title="Delete Role">
                    //                             <em class="icon ni ni-trash"></em><span>Delete</span>
                    //                         </a>
                    //                     </li>';
                    //     }
                    // }
                    $actions .= '</ul></div></div>';
                // }
                return $actions;
            })
            ->addColumn('title', function ($record) {
//                $url = auth()->user()->hasPermissionTo('edit_role') ? route('roles.edit', $record->id) : '#';
//                return '<a href="'. $url .'" class="link" data-toggle="tooltip" data-placement="top" title="Edit Role">'.$record->name.'</a>';
            })
            ->rawColumns(['actions', 'title'])
            ->addIndexColumn()->make(true);
    }

    public static function formatPermissions($permissions)
    {
        $permissionGroups = [];
        foreach ($permissions as $permission) {
            $permissionGroups[$permission->group_name][] = $permission;
        }
        return $permissionGroups;
    }
}
