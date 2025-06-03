<?php
namespace App\Repo;

use App\Models\Core\Auth\Role;
use App\Models\Location;
use App\Models\StorageType;
use App\Models\User;
use App\Models\Warehouse;
use App\Repo\Interfaces\StorageTypeInterface;
use App\Repo\Interfaces\UserInterface;
use App\Repo\Interfaces\WarehouseInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserClass implements UserInterface {

    public function saveUser($request)
    {
        $name = 0;
        if ($request->hasFile('file')) {
            $uniqueid = uniqid();
            $original_name = $request->file('file')->getClientOriginalName();
            $size = $request->file('file')->getSize();
            $extension = $request->file('file')->getClientOriginalExtension();
            $name = Carbon::now()->format('Ymd') . '_' . $uniqueid . '.' . $extension;
            $imagepath = url('/storage/uploads/user-images/' . $name);
            $path = $request->file('file')->storeAs('public/uploads/user-images/', $name);
        }else{

            $name='no_avatar.png';
        }


        // TODO: Implement saveUser() method.
        $sy =new User();
        $sy->first_name=$request->first_name;
        $sy->last_name=$request->last_name;
        $sy->password = Hash::make("12345678");
        $sy->email =$request->email;
        $sy->status_id =1;
        $sy->is_in_employee =1;
        $sy->phone=$request->phone;
        $sy->address=$request->address;
        if($name != 0)
        {
            $sy->avatar=$name;
        }
        $sy->user_type=$request->role;
        $sy->status=$request->status;
        if($sy->save()){
            $role = Role::find($request->role);
            $userrs = $sy->assignRole($role);
            if($userrs)
            {
                return response()->json(['success' => 'Record save successfully'], 200);
            }

        }
    }

    public function getUser()
    {
        // TODO: Implement getUser() method.
        $qry=User::with('role');
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteUser($id)
    {
        // TODO: Implement deleteUser() method.
        $country=User::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;
    }

    public function editUser($id)
    {
        // TODO: Implement editUser() method.
        return $country = User::find($id);
    }

    public function updateUser($request)
    {
        $name = 0;
        if ($request->hasFile('e_file')) {
            $uniqueid = uniqid();
            $original_name = $request->file('e_file')->getClientOriginalName();
            $size = $request->file('e_file')->getSize();
            $extension = $request->file('e_file')->getClientOriginalExtension();
            $name = Carbon::now()->format('Ymd') . '_' . $uniqueid . '.' . $extension;
            $imagepath = url('/storage/uploads/user-images/' . $name);
            $path = $request->file('e_file')->storeAs('public/uploads/user-images/', $name);
        }
        // TODO: Implement updateUser() method.
        $sy=User::find($request->id);
        $sy->first_name=$request->e_first_name;
        $sy->last_name=$request->e_last_name;
        $sy->email=$request->e_email;
        $sy->phone=$request->e_phone;
        if($name != 0)
        {
            $sy->avatar=$name;
        }
        $sy->address=$request->e_address;
        $sy->user_type=$request->e_role;
        $sy->status=$request->e_status;
        if($sy->save()){
            $role = Role::find($request->e_role);
            $userrs = $sy->assignRole($role);
            if($userrs)
            {
                return 1;
            }

        }

    }

    public function updateProfile($request)
    {
        // TODO: Implement updateProfile() method.
        $name = 0;
        if ($request->hasFile('avatar')) {
            $uniqueid = uniqid();
            $original_name = $request->file('avatar')->getClientOriginalName();
            $size = $request->file('avatar')->getSize();
            $extension = $request->file('avatar')->getClientOriginalExtension();
            $name = Carbon::now()->format('Ymd') . '_' . $uniqueid . '.' . $extension;
            $imagepath = url('/storage/uploads/user-images/' . $name);
            $path = $request->file('avatar')->storeAs('public/uploads/user-images/', $name);
        }

        $sy=User::find($request->id);
        $sy->first_name=$request->first_name;
        $sy->last_name=$request->last_name;
        $sy->email=$request->email;
        $sy->phone=$request->phone;
        if($name !=0 )
        {
            $sy->avatar=$name;
        }
        $sy->address=$request->address;
        $sy->save();
        return 1;
    }

    public function updatePassword($request)
    {

        $auth_id = auth()->id();
        $sy=User::find($auth_id);
        $sy->password = Hash::make($request->password);
        $sy->save();
        return 1;
    }
}
