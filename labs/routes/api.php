<?php

use App\Http\Controllers\ChangeLogsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\UserAndRolesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleAndPermissionController;
use App\Http\Controllers\Auth2faController;
use App\Http\Controllers\GitWebHookController;
use App\Http\Controllers\LogsController;
use App\Jobs\ReportJob;


Route::prefix('ref/policy')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/role',[RoleController::class,'getRoleList'] );
    Route::get('/role/{id}', [RoleController::class, 'getRole']);
    Route::post('/role', [RoleController::class, 'createRole']);
    Route::put('/role/{id}', [RoleController::class, 'updateRole']);
    Route::delete('/role/{id}', [RoleController::class, 'deleteRole']);
    Route::delete('/role/{id}/soft', [RoleController::class, 'deleteRoleSoft']);
    Route::post('/role/{id}/restore', [RoleController::class, 'restoreRole']);


    // permissions
    Route::get('/permissions',[PermissionsController::class,'getPermissionsList'] );
    Route::get('/permissions/{id}',[PermissionsController::class,'getPermissions']);
    Route::post('/permissions', [PermissionsController::class, 'createPermission']);
    Route::put('/permissions/{id}', [PermissionsController::class, 'updatePermission']);
    Route::delete('/permissions/{id}',[PermissionsController::class, 'deletePermission'] );
    Route::delete('/permissions/{id}/soft', [PermissionsController::class, 'deletePermissionsSoft']);
    Route::post('/permissions/{id}/restore', [PermissionsController::class, 'restorePermission']);


});

Route::prefix('ref')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/user',[UserAndRolesController::class,'getUsers'] );
    Route::get('/user/{id}/role', [UserAndRolesController::class, 'getUserRoles']);
    Route::post('/user/{id}/role', [UserAndRolesController::class, 'AddUserRole']);
    Route::delete('user/{id}/role/{role_id}', [UserAndRolesController::class, 'RemoveUserRole']);

   // role and permissions
    Route::get('/role/{id}/permission', [RoleAndPermissionController::class, 'getPermissionsRole'] );
    Route::post('/role/{id}/permission', [RoleAndPermissionController::class, 'AddPermissionToRole']);
    Route::delete('/role/{id}/permission/{permission_id}', [RoleAndPermissionController::class, 'RemovePermissionToRole']);


});


Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Предоставление возможности авторизации по 2fa
Route::prefix('2fa')->middleware(['auth:sanctum'])->group(function () {
    Route::post('/toggle', [Auth2faController::class, 'toggleTwoFactorAuth']);
    Route::get('/status', [Auth2faController::class, 'twoFactorStatus']);
});


Route::prefix('auth')->middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/out', [AuthController::class, 'out']);
    Route::get('/tokens', [AuthController::class, 'tokens']);
    Route::post('/out_all', [AuthController::class, 'out_all']);
});


Route::prefix('/test')->middleware('role:Admin')->group(function () {
    Route::post('/user/{id}/role', [UserAndRolesController::class, 'AddUserRole']);
});

Route::prefix('/ref')->middleware('story:Admin')->group(function () {
    Route::get('/user/{id}/story', [ChangeLogsController::class, 'getUserStory']); // Истрия логирования пользователей
    Route::get('/policy/permissions/{id}/story', [ChangeLogsController::class, 'getPermissionsStory']); // История логирования разрешений
    Route::get('/policy/role/{id}/story', [ChangeLogsController::class, 'getRoleStory']); // История логирование ролей

    Route::put('role/{roleId}/rollback/{historyId}', [ChangeLogsController::class, 'RollbackRole']); // Откат изменения для роли
    Route::put('user/{roleId}/rollback/{historyId}', [ChangeLogsController::class, '']); // Откат изменения для пользоватлея
    Route::put('permission/{roleId}/rollback/{historyId}', [ChangeLogsController::class, 'RollbackPermission']); // Откат изменения разрешений

});

Route::post('/hooks/git', [GitWebHookController::class, 'hooks']);

Route::prefix('logs')->middleware(['auth:sanctum', 'log'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
});


Route::post('hooks/git', [GitWebHookController::class, 'hooks']);

Route::prefix('logs/auth')->middleware('log')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::get('/run-job', function () {
    ReportJob::dispatch();
    return 'Job в очереди';
});

Route::prefix('ref/logs')->middleware(['auth:sanctum', 'role:Admin'] )->group(function () {
    Route::get('/request', [LogsController::class, 'requests']);
    Route::get('/request/{id}', [LogsController::class, 'request']);
    Route::delete('/request/{id}', [LogsController::class, 'destroy']);
});
