<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Core\Auth\User;
use App\Services\Core\Auth\UserService;
use Illuminate\Http\Request;

$user = User::query()->whereHas('profile')->with('profile')->first();

$request = Request::create('/test', 'PATCH', [
    'first_name' => $user->first_name,
    'last_name' => $user->last_name,
    'email' => $user->email,
    'employee_id' => $user->profile->employee_id,
    'gender' => $user->profile->gender ?: 'male',
    'phone_number' => '+971543889921',
]);
app()->instance('request', $request);

$service = resolve(UserService::class);
try {
    $service->setModel($user)->validate((int) $user->id);
    echo "PASS explicit validate\n";
} catch (Throwable $e) {
    echo "FAIL: " . $e->getMessage() . "\n";
}
