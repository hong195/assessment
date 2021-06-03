<?php

use Doctrine\ORM\EntityManagerInterface;
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
    $em = app()->make(EntityManagerInterface::class);

    $repo = app()->make(\Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisRepository::class);


    $array = [new EmployeeId('89974b0e-7ba7-41fb-84c7-ea1ef0588872')];
    dd($repo->findById(new EfficiencyAnalysisId('3b223208-74bc-4fbd-8e7f-c091b64c84a2')));
    return view('welcome');
});
