<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Tenant\Dashboard\EmployeeDashboardController;
use App\Http\Controllers\Tenant\Employee\AttendanceController;
use App\Http\Controllers\Tenant\Employee\AttendancePunchInController;
use App\Http\Controllers\Tenant\Settings\GeneralSettingController;
use App\Http\Controllers\Tenant\Employee\ManualAttendanceController;
use App\Http\Controllers\Core\Auth\User\UserUpdateController;
use App\Http\Controllers\Core\Auth\User\UserPasswordController;
use Illuminate\Http\Request;
use App\Http\Controllers\Tenant\Attendance\AttendanceRequestController;
use App\Http\Controllers\Core\Log\ActivityLogController;
use App\Http\Controllers\Tenant\Employee\EmployeeController;
use App\Http\Controllers\Tenant\Attendance\AttendanceLogController;
use App\Http\Controllers\Tenant\Attendance\AttendanceStatusController;
use App\Http\Controllers\Tenant\Employee\EmployeeLeaveAllowanceController;
use App\Http\Controllers\Tenant\Employee\DocumentController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\Api\McpBlogController;
use App\Http\Controllers\Api\McpStreamController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//auth api
Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

Route::post('/webhook/receive-customer', [WebhookController::class, 'handleCustomerResponse']);
Route::post('/webhook/receive-product', [WebhookController::class, 'handleProductResponse']);
Route::post('/webhook/receive-service', [WebhookController::class, 'handleServiceResponse']);
Route::post('/webhook/receive-invoice', [WebhookController::class, 'handleInvoiceResponse']);
Route::post('/webhook/receive-payment', [WebhookController::class, 'handlePaymentResponse']);

// MCP blog tools (token auth) — Cursor stdio MCP + Claude.ai remote connector
Route::middleware(['mcp.blog', 'throttle:60,1'])->group(function () {
    // Streamable HTTP MCP for Claude.ai → URL: /api/mcp  or  /api/mcp/claude/{token}
    Route::match(['GET', 'POST', 'DELETE'], '/mcp', [McpStreamController::class, 'handle']);
    Route::match(['GET', 'POST', 'DELETE'], '/mcp/claude/{mcpToken}', [McpStreamController::class, 'handle']);

    Route::prefix('mcp')->group(function () {
        Route::get('/blogs', [McpBlogController::class, 'index']);
        Route::post('/blogs', [McpBlogController::class, 'store']);
    });
});


//Route::apiResource('users',[AuthController::class, 'users'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/users', function (Request $request) {
    return $request->user();
});



Route::middleware(['auth:sanctum'])->group(function () {

    //user related api
    Route::post('/user',[AuthController::class, 'getUser']);
    Route::get('/getUserProfile',[UserUpdateController::class, 'editUserApi'])->middleware('auth:sanctum');
    Route::post('/updateprofile',[UserUpdateController::class, 'updateApi']);
    Route::post('/updatePassword',[UserPasswordController::class, 'updatePasswordApi']);

    //employee dashboard api
    Route::get('/employeeAttendance',[EmployeeDashboardController::class, 'employeeAttendance']);
    Route::get('/announcements',[EmployeeDashboardController::class, 'announcementsApi']);
    Route::get('/employeeMonthlyAttendanceLog',[EmployeeDashboardController::class, 'employeeMonthlyAttendanceLog']);
    Route::get('/employeeLeaveSummaries',[EmployeeDashboardController::class, 'employeeLeaveSummariesApi']);

    //employee attendence api
    Route::get('/checkPunchIn',[AttendancePunchInController::class, 'checkPunchInAPI']);
    Route::post('/punch-In',[AttendanceController::class, 'punchInApi']);
    Route::post('/punch-Out',[AttendanceController::class, 'punchOutApi']);
    Route::post('/attendanceRequest',[ManualAttendanceController::class, 'attendanceRequestApi']);
    Route::get('/getAttendanceRequests',[AttendanceRequestController::class, 'getRequestsApi']);

    //app settings api
    Route::get('/settings',[GeneralSettingController::class, 'getAppSettings']);
    Route::get('/activity-log',[ActivityLogController::class, 'userActivityLogAPI']);
    Route::get('/job-desk',[EmployeeController::class, 'jobdeskAPI']);
    Route::get('/getDocuments',[DocumentController::class, 'getDocumentsAPI']);
    Route::post('/attendanceslog',[AttendanceLogController::class, 'attendencelogAPI']);
    Route::post('/attendenceRequestCancel',[AttendanceStatusController::class, 'updateCancelAPI']);
    Route::post('/leaveAllowance',[EmployeeLeaveAllowanceController::class, 'leaveAllowanceAPI']);
});