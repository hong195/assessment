<?php

use App\Http\Controllers\CompletedPharmacyFinalGradeController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\CriteriaOptionsController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\FinalGradeAssessmentsController;
use App\Http\Controllers\FinalGradeController;
use App\Http\Controllers\PharmaciesController;
use App\Http\Controllers\PharmacyEmployeesController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

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
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
    'namespace' => 'App\Http\Controllers\Auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::get('pharmacy/{pharmacyId}/final-grade', [CompletedPharmacyFinalGradeController::class, 'show']);

Route::apiResource('final-grades', FinalGradeController::class)
    ->only(['index', 'store', 'show']);

Route::apiResource('final-grade.assessments', FinalGradeAssessmentsController::class)
    ->only(['store', 'update', 'destroy', 'show']);

Route::apiResource('criteria', CriteriaController::class)
    ->only(['index', 'store', 'update', 'destroy']);

Route::apiResource('pharmacy.employees', PharmacyEmployeesController::class)
    ->only(['index']);

Route::apiResource('employees', EmployeesController::class)
    ->only(['index', 'store', 'show', 'update', 'destroy']);

Route::apiResource('criteria.options', CriteriaOptionsController::class)
    ->only(['store', 'show', 'update', 'index', 'destroy']);

Route::apiResource('pharmacies', PharmaciesController::class);

Route::apiResource('users', UsersController::class);

