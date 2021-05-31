<?php

use Domain\Model\Assessment\AssessmentId;
use Domain\Model\Assessment\Check;
use Domain\Model\Assessment\Criterion;
use Domain\Model\Assessment\Option;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisId;
use Domain\Model\Employee\Employee;
use Domain\Model\Employee\EmployeeId;
use Domain\Model\User\User;
use Illuminate\Support\Facades\Route;
use Tests\Unit\Domain\Model\Builders\AssessmentBuilder;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $repo = \LaravelDoctrine\ORM\Facades\EntityManager::getRepository(\Domain\Model\EfficiencyAnalysis\EfficiencyAnalysis::class);

    dd($repo->findByMonthDate(new \Domain\Model\EfficiencyAnalysis\Month(2020, 1)));
    return view('welcome');
});
