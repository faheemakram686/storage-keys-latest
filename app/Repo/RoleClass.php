<?php
namespace App\Repo;

use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\Type;
use App\Models\Location;
//use App\Models\Role;
use App\Models\StorageType;
use App\Models\User;
use App\Models\Warehouse;
use App\Repo\Interfaces\RoleInterface;
use App\Repo\Interfaces\StorageTypeInterface;
use App\Repo\Interfaces\UserInterface;
use App\Repo\Interfaces\WarehouseInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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

    public function getRole()
    {
        // TODO: Implement getRole() method.
        $qry=Role::query();
        $qry=$qry->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteRole($id)
    {
        // TODO: Implement deleteRole() method.
        $country=Role::find($id);
        $country->delete();
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
