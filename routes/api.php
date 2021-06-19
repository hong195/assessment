<?php

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\EfficiencyAnalysesController;
use \App\Http\Controllers\EfficiencyAnalysesAssessmentsController;
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
Route::apiResource('efficiency-analyzes', EfficiencyAnalysesController::class)
    ->only(['index', 'store', 'show']);

Route::apiResource('efficiency-analyzes.assessments', EfficiencyAnalysesAssessmentsController::class)
    ->only(['store', 'update', 'destroy']);
