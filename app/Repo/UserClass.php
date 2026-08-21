<?php
namespace App\Repo;

use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\User as AuthUser;
use App\Models\User;
use App\Repo\Interfaces\UserInterface;
use App\Services\Core\Auth\UserRoleSyncService;
use App\Services\Tenant\Employee\EmployeeService;
use Carbon\Carbon;
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
            'email'      => ['required', 'string', 'email', 'max:255', unique_active_user_email_rule()],
            'password'   => 'required|string|min:8|confirmed',
            'phone'      => 'nullable|string|max:20',
            'address'    => 'nullable|string|max:255',
            // Unified single staff role (backward compat: portal_role / hrm_role / role)
            'role'       => 'nullable|integer|exists:roles,id',
            'portal_role' => 'nullable|integer|exists:roles,id',
            'hrm_role'    => 'nullable|integer|exists:roles,id',
            'status'     => 'required|integer',
            'file'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'gender'     => 'required|in:male,female,other',
            'employee_id' => ['required', 'string', 'min:2', unique_active_employee_id_rule()],
            'department_id' => 'required|integer|exists:departments,id',
            'designation_id' => 'required|integer|exists:designations,id',
            'employment_status_id' => 'required|integer|exists:employment_statuses,id',
            'salary' => 'required|numeric|min:0',
            'joining_date' => 'required|date',
        ]);

        $validator->after(function ($validator) use ($request) {
            $roleId = $this->resolveStaffRoleId($request);
            if (!$roleId) {
                $validator->errors()->add('role', 'A staff role is required.');
                return;
            }
            $sync = resolve(UserRoleSyncService::class);
            $role = Role::with('type')->find($roleId);
            if (!$role || !$sync->isStaffRole($role)) {
                $validator->errors()->add('role', 'Only staff roles can be assigned. App Admin cannot be assigned.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $sync = resolve(UserRoleSyncService::class);
            $roleId = $this->resolveStaffRoleId($request);

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
            $user->user_type     = $roleId;
            $user->status        = $request->status;
            $user->save();

            $sync->syncStaffRole($user, $roleId);

            $this->syncEmployeeFields($user, $request, true);

            DB::commit();

            return response()->json([
                'success' => 'User created successfully'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User creation failed: ' . $e->getMessage());

            return response()->json([
                'error' => $e->getMessage() ?: 'Something went wrong while creating the user.'
            ], 500);
        }
    }

    public function getUser()
    {
        return User::with('roles.type')
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
        return User::with(['roles.type', 'profile', 'department', 'designation', 'employmentStatus'])
            ->findOrFail($id);
    }

    public function updateUser($request)
    {
        $validator = Validator::make($request->all(), [
            'id'         => 'required|exists:users,id',
            'e_first_name' => 'required|string|max:255',
            'e_last_name'  => 'nullable|string|max:255',
            'e_email'      => ['required', 'string', 'email', 'max:255', unique_active_user_email_rule($request->id)],
            'e_phone'      => 'nullable|string|max:20',
            'e_address'    => 'nullable|string|max:255',
            'e_role'       => 'nullable|integer|exists:roles,id',
            'e_portal_role' => 'nullable|integer|exists:roles,id',
            'e_hrm_role'    => 'nullable|integer|exists:roles,id',
            'e_status'     => 'required|integer',
            'e_file'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'password'     => 'nullable|string|min:8|confirmed',
            'e_gender'     => 'required|in:male,female,other',
            'e_employee_id' => ['required', 'string', 'min:2', unique_active_employee_id_rule($request->id)],
            'e_department_id' => 'required|integer|exists:departments,id',
            'e_designation_id' => 'required|integer|exists:designations,id',
            'e_employment_status_id' => 'required|integer|exists:employment_statuses,id',
            'e_joining_date' => 'required|date',
        ]);

        $validator->after(function ($validator) use ($request) {
            $roleId = $this->resolveStaffRoleId($request, true);
            if (!$roleId) {
                $validator->errors()->add('e_role', 'A staff role is required.');
                return;
            }
            $sync = resolve(UserRoleSyncService::class);
            $role = Role::with('type')->find($roleId);
            if (!$role || !$sync->isStaffRole($role)) {
                $validator->errors()->add('e_role', 'Only staff roles can be assigned. App Admin cannot be assigned.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $sync = resolve(UserRoleSyncService::class);
            $user = User::findOrFail($request->id);
            $roleId = $this->resolveStaffRoleId($request, true);

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
            $user->user_type  = $roleId;
            $user->status     = $request->e_status;

            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            $sync->syncStaffRole($user, $roleId);

            $this->syncEmployeeFields($user, $request, false);

            DB::commit();

            return response()->json([
                'success' => 'User updated successfully'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User update failed: ' . $e->getMessage());

            return response()->json([
                'error' => $e->getMessage() ?: 'Something went wrong while updating the user.'
            ], 500);
        }
    }

    public function updateProfile($request)
    {
        $name = 0;
        if ($request->hasFile('avatar')) {
            $uniqueid = uniqid();
            $extension = $request->file('avatar')->getClientOriginalExtension();
            $name = Carbon::now()->format('Ymd') . '_' . $uniqueid . '.' . $extension;
            $request->file('avatar')->storeAs('public/uploads/user-images/', $name);
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

    /**
     * Resolve a single staff role id from request (create or edit prefixes).
     */
    protected function resolveStaffRoleId($request, bool $edit = false): ?int
    {
        if ($edit) {
            $roleId = $request->e_role ?: $request->e_portal_role ?: $request->e_hrm_role;
        } else {
            $roleId = $request->role ?: $request->portal_role ?: $request->hrm_role;
        }

        return $roleId ? (int) $roleId : null;
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
