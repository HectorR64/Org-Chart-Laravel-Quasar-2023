<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Security\AuthController;
use App\Http\Controllers\Modules\DepartmentController;
use App\Http\Controllers\Modules\OrganizationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Ruta de registro y login con 3 intentos por minuto
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,1');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:3,1');
//Ruta para cerrar sesion
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

//Obtiene datos de los departamentos, con un limite de 30 peticiones por minuto
Route::get('/departments', [DepartmentController::class, 'index'])->middleware('auth:api');

//Middleware CRUD con auth, alias organization y con un limite de 20 peticiones por minuto
Route::prefix('organization')->middleware('auth:api')->group(function () {
    Route::get('/index', [OrganizationController::class, 'index']);
    Route::post('/post', [OrganizationController::class, 'store']);
    Route::put('/update/{id}', [OrganizationController::class, 'update']);
    Route::delete('/delete/{id}', [OrganizationController::class, 'delete']);
    Route::get('/excel', [OrganizationController::class, 'excel']);
});
