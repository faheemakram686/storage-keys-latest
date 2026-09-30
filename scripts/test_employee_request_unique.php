<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Core\Auth\User;
use App\Http\Requests\Tenant\Employee\EmployeeRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

$user = User::query()->whereHas('profile', function ($q) {
    $q->whereNotNull('employee_id');
})->with(['profile', 'department', 'designation', 'employmentStatus'])->first();

if (!$user) {
    echo "no user\n";
    exit(1);
}

Auth::loginUsingId($user->id);

$data = [
    'first_name' => $user->first_name ?: 'Test',
    'last_name' => $user->last_name ?: 'User',
    'email' => $user->email,
    'employee_id' => $user->profile->employee_id,
    'gender' => $user->profile->gender ?: 'male',
    'department_id' => optional($user->department)->id ?: 1,
    'designation_id' => optional($user->designation)->id ?: 1,
    'employment_status_id' => optional($user->employmentStatus)->id ?: 1,
    'roles' => [],
    'id' => $user->id,
];

echo "Testing user {$user->id} email={$user->email} emp={$user->profile->employee_id}\n";

// Simulate EmployeeRequest rules with route employee model
$ignoreUserId = $user->id;
$rules = [
    'email' => [
        'required',
        'email',
        Rule::unique('users', 'email')->ignore($ignoreUserId),
    ],
    'employee_id' => [
        'required',
        'min:2',
        Rule::unique('profiles', 'employee_id')->ignore($ignoreUserId, 'user_id'),
    ],
    'department_id' => 'required|integer',
    'designation_id' => 'required|integer',
    'employment_status_id' => 'required|integer',
    'gender' => 'nullable|in:male,female,other',
];

$v = Validator::make($data, $rules);
echo $v->fails() ? "FAIL with model ignore\n" : "PASS with model ignore\n";
if ($v->fails()) print_r($v->errors()->toArray());

// Soft deletes included?
$v2 = Validator::make($data, [
    'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($ignoreUserId)->whereNull('deleted_at')],
    'employee_id' => ['required', 'min:2', Rule::unique('profiles', 'employee_id')->ignore($ignoreUserId, 'user_id')],
]);
echo $v2->fails() ? "FAIL with whereNull deleted\n" : "PASS with whereNull deleted\n";
if ($v2->fails()) print_r($v2->errors()->toArray());

// What if gender is title case from DB?
$data['gender'] = ucfirst($data['gender'] ?? 'male');
$v3 = Validator::make($data, $rules);
echo $v3->fails() ? "FAIL gender={$data['gender']}\n" : "PASS gender={$data['gender']}\n";
if ($v3->fails()) print_r($v3->errors()->toArray());

// Check soft deleted users with same email
$trashed = User::onlyTrashed()->where('email', $user->email)->count();
echo "trashed same email={$trashed}\n";

// Check profile user_id mismatch
$profile = $user->profile;
echo "profile.user_id={$profile->user_id} user.id={$user->id} match=" . ((int)$profile->user_id === (int)$user->id ? 'yes' : 'NO') . "\n";

// Simulate request without route employee (ignore null)
$v4 = Validator::make($data, [
    'email' => ['required', 'email', Rule::unique('users', 'email')->ignore(null)],
    'employee_id' => ['required', 'min:2', Rule::unique('profiles', 'employee_id')->ignore(null, 'user_id')],
]);
echo $v4->fails() ? "FAIL null ignore (this is the bug if route param missing)\n" : "PASS null ignore\n";
if ($v4->fails()) print_r($v4->errors()->toArray());
