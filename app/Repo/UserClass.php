<?php
namespace App\Repo;

use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\User as AuthUser;
use App\Models\Location;
use App\Models\StorageType;
use App\Models\User;
use App\Models\Warehouse;
use App\Repo\Interfaces\StorageTypeInterface;
use App\Repo\Interfaces\UserInterface;
use App\Repo\Interfaces\WarehouseInterface;
use App\Services\Tenant\Employee\EmployeeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class UserClass implements UserInterface {

    public function saveUser($request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'nullable|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users,email',
            'password'   => 'required|string|min:8|confirmed',
            'phone'      => 'nullable|string|max:20',
            'address'    => 'nullable|string|max:255',
            'role'       => 'required|integer|exists:roles,id',
            'status'     => 'required|integer',
            'file'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'gender'     => 'required|in:male,female,other',
            'employee_id' => 'required|string|min:2|unique:profiles,employee_id',
            'department_id' => 'required|integer|exists:departments,id',
            'designation_id' => 'required|integer|exists:designations,id',
            'employment_status_id' => 'required|integer|exists:employment_statuses,id',
            'salary' => 'required|numeric|min:0',
            'joining_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {

            $avatar = 'no_avatar.png';
            if ($request->hasFile('file')) {
                $uniqueId = uniqid();
                $extension = $request->file('file')->getClientOriginalExtension();
                $avatar = Carbon::now()->format('Ymd') . '_' . $uniqueId . '.' . $extension;
                $request->file('file')->storeAs('public/uploads/user-images/', $avatar);
            }


            $user = new User();
            $user->first_name    = $request->first_name;
            $user->last_name     = $request->last_name;
            $user->email         = $request->email;
            $user->password      = Hash::make($request->password);
            $user->status_id     = 1;
            $user->is_in_employee= 1;
            $user->phone         = $request->phone;
            $user->address       = $request->address;
            $user->avatar        = $avatar;
            $user->user_type     = $request->role;
            $user->status        = $request->status;
            $user->save();

            $role = Role::findOrFail($request->role);
            $user->roles()->sync([$role->id]);

            $this->syncEmployeeFields($user, $request, true);

            DB::commit();

            return response()->json([
                'success' => 'User created successfully'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User creation failed: ' . $e->getMessage());

            return response()->json([
                'error' => 'Something went wrong while creating the user.'
            ], 500);
        }


    }

    public function getUser()
    {
        return User::with('roles')
            ->where('is_deleted', 0)
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->is_deleted = 1;
        $user->save();
        $user->delete();

        return 1;
    }

    public function editUser($id)
    {
        return User::with(['roles', 'profile', 'department', 'designation', 'employmentStatus'])
            ->findOrFail($id);
    }

    public function updateUser($request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'id'         => 'required|exists:users,id',
            'e_first_name' => 'required|string|max:255',
            'e_last_name'  => 'nullable|string|max:255',
            'e_email'      => 'required|string|email|max:255|unique:users,email,' . $request->id,
            'e_phone'      => 'nullable|string|max:20',
            'e_address'    => 'nullable|string|max:255',
            'e_role'       => 'required|integer|exists:roles,id',
            'e_status'     => 'required|integer',
            'e_file'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'password'     => 'nullable|string|min:8|confirmed',
            'e_gender'     => 'required|in:male,female,other',
            'e_employee_id' => 'required|string|min:2|unique:profiles,employee_id,' . $request->id . ',user_id',
            'e_department_id' => 'required|integer|exists:departments,id',
            'e_designation_id' => 'required|integer|exists:designations,id',
            'e_employment_status_id' => 'required|integer|exists:employment_statuses,id',
            'e_joining_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $user = User::findOrFail($request->id);


            if ($request->hasFile('e_file')) {
                $uniqueId = uniqid();
                $extension = $request->file('e_file')->getClientOriginalExtension();
                $fileName = Carbon::now()->format('Ymd') . '_' . $uniqueId . '.' . $extension;


                if ($user->avatar && $user->avatar !== 'no_avatar.png' && Storage::exists('public/uploads/user-images/' . $user->avatar)) {
                    Storage::delete('public/uploads/user-images/' . $user->avatar);
                }

                $request->file('e_file')->storeAs('public/uploads/user-images/', $fileName);
                $user->avatar = $fileName;
            }


            $user->first_name = $request->e_first_name;
            $user->last_name  = $request->e_last_name;
            $user->email      = $request->e_email;
            $user->phone      = $request->e_phone;
            $user->address    = $request->e_address;
            $user->user_type  = $request->e_role;
            $user->status     = $request->e_status;


            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            $role = Role::findOrFail($request->e_role);
            $user->roles()->sync([$role->id]);

            $this->syncEmployeeFields($user, $request, false);

            DB::commit();

            return response()->json([
                'success' => 'User updated successfully'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User update failed: ' . $e->getMessage());

            return response()->json([
                'error' => 'Something went wrong while updating the user.'
            ], 500);
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

    protected function syncEmployeeFields($user, $request, bool $withSalary): void
    {
        $isCreate = $withSalary;

        $attributes = $isCreate ? [
            'gender' => $request->gender,
            'employee_id' => $request->employee_id,
            'department_id' => $request->department_id,
            'designation_id' => $request->designation_id,
            'employment_status_id' => $request->employment_status_id,
            'joining_date' => $request->joining_date,
            'salary' => $request->salary,
        ] : [
            'gender' => $request->e_gender,
            'employee_id' => $request->e_employee_id,
            'department_id' => $request->e_department_id,
            'designation_id' => $request->e_designation_id,
            'employment_status_id' => $request->e_employment_status_id,
            'joining_date' => $request->e_joining_date,
        ];

        $service = resolve(EmployeeService::class)
            ->setModel(AuthUser::findOrFail($user->id))
            ->setAttributes($attributes)
            ->saveEmployeeId()
            ->saveJoiningDate()
            ->setIsInvite(false)
            ->assignToDepartment()
            ->assignToDesignation()
            ->assignEmploymentStatus();

        if ($withSalary) {
            $service->saveSalary();
        }
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
