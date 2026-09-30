<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Core\Auth\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

$user = User::query()->whereHas('profile')->with('profile')->first();
if (!$user) {
    echo "no user\n";
    exit;
}

$data = [
    'first_name' => $user->first_name,
    'email' => $user->email,
    'employee_id' => $user->profile->employee_id,
    'gender' => $user->profile->gender ?: 'male',
    'phone_number' => '+971543889921',
];

echo "user={$user->id} email={$user->email} emp={$user->profile->employee_id}\n";

$v = Validator::make($data, [
    'first_name' => 'required',
    'email' => [
        'required',
        'email',
        Rule::unique('users', 'email')->ignore(optional($user)->id)
    ],
    'employee_id' => 'required|min:2|unique:profiles,employee_id,'.optional($user)->id.',user_id',
    'gender' => 'required'
]);

if ($v->fails()) {
    echo "FAIL\n";
    print_r($v->errors()->toArray());
} else {
    echo "PASS with ignore\n";
}

// Without ignore - should fail
$v2 = Validator::make($data, [
    'email' => ['required', 'email', Rule::unique('users', 'email')],
    'employee_id' => 'required|min:2|unique:profiles,employee_id',
]);
echo $v2->fails() ? "without ignore FAIL (expected)\n" : "without ignore PASS (unexpected)\n";

// What if model id is wrong/null
$v3 = Validator::make($data, [
    'email' => ['required', 'email', Rule::unique('users', 'email')->ignore(null)],
    'employee_id' => 'required|min:2|unique:profiles,employee_id,,user_id',
]);
echo $v3->fails() ? "null ignore FAIL\n" : "null ignore PASS\n";
print_r($v3->errors()->toArray());
