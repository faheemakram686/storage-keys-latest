<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Core\Auth\User;
use App\Http\Requests\Tenant\Employee\EmployeeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

$user = User::query()->whereHas('profile', fn ($q) => $q->whereNotNull('employee_id'))
    ->with(['profile', 'department', 'designation', 'employmentStatus'])
    ->first();

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
    'id' => $user->id,
];

// Simulate FormRequest with route parameter bound
$request = Request::create('/app/employees/' . $user->id, 'PATCH', $data);
$request->setUserResolver(fn () => $user);
$route = Route::getRoutes()->getByName('tenant.employees.update');
if (!$route) {
    // try matching
    $route = collect(Route::getRoutes())->first(function ($r) {
        return str_contains($r->uri(), 'employees/{employee}')
            && in_array('PATCH', $r->methods())
            && str_contains($r->getActionName(), 'EmployeeController@update');
    });
}

if (!$route) {
    echo "route not found\n";
    // Manual FormRequest simulation
    $form = EmployeeRequest::createFrom($request);
    // Can't easily set route - test fallback via id in payload
    $ref = new ReflectionClass($form);
    // Use validator with our new rules logic by instantiating and calling protected via subclass
}

class TestEmployeeRequest extends EmployeeRequest {
    public $testEmployee;
    public function route($param = null, $default = null)
    {
        if ($param === 'employee' || $param === null) {
            return $param === null ? null : $this->testEmployee;
        }
        return parent::route($param, $default);
    }
    public function exposeIgnore() { return $this->employeeIdToIgnore(); }
}

// Case 1: route employee model present
$r1 = TestEmployeeRequest::create('/x', 'PATCH', $data);
$r1->testEmployee = $user;
$r1->setContainer(app())->setRedirector(app('redirect'));
$r1->validateResolved();
echo "with route model: PASS\n";

// Case 2: route employee missing, fallback to id in payload
$r2 = TestEmployeeRequest::create('/x', 'PATCH', $data);
$r2->testEmployee = null;
$r2->setContainer(app())->setRedirector(app('redirect'));
try {
    $r2->validateResolved();
    echo "fallback id payload: PASS ignore=" . $r2->exposeIgnore() . "\n";
} catch (Throwable $e) {
    echo "fallback id payload: FAIL " . $e->getMessage() . "\n";
}

// Case 3: POST create should fail on existing email
$r3 = TestEmployeeRequest::create('/x', 'POST', $data);
$r3->testEmployee = null;
$r3->setContainer(app())->setRedirector(app('redirect'));
try {
    $r3->validateResolved();
    echo "post create: PASS (unexpected)\n";
} catch (Throwable $e) {
    echo "post create: FAIL as expected\n";
}
