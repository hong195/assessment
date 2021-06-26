<?php

use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\CriteriaOptionsController;
use App\Http\Controllers\FinalGradeAssessmentsController;
use App\Http\Controllers\FinalGradeController;
use App\Http\Controllers\PharmaciesController as PharmaciesControllerAlias;
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
Route::apiResource('final-grades', FinalGradeController::class)
    ->only(['index', 'store', 'show']);

Route::apiResource('final-grade.assessments', FinalGradeAssessmentsController::class)
    ->only(['store', 'update', 'destroy']);

Route::apiResource('criteria', CriteriaController::class)
    ->only(['index', 'store', 'update', 'destroy']);

Route::apiResource('criteria.options', CriteriaOptionsController::class)
    ->only(['store', 'update', 'destroy']);

Route::apiResource('pharmacies', PharmaciesControllerAlias::class);
