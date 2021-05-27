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
//    $repo = \LaravelDoctrine\ORM\Facades\EntityManager::getRepository(User::class);
//
//
//    \LaravelDoctrine\ORM\Facades\EntityManager::persist(\Tests\Unit\Domain\Model\Builders\UserBuilder::aUser()->build());

    $criterion = [];

    $criterion[] = new Criterion('Ethics',
        [new Option('yes', 1), new Option('no', 0)],
        'yes');

    $criterion[] = new Criterion('Kindness',
        [new Option('yes', 1), new Option('no', 0)],
        'no');

    $criterion[] = new Criterion('Additional care',
        [new Option('partially', 0.5), new Option('no', 0)],
        'partially');



    $employee = EmployeeId::next();
    $month = new \Domain\Model\EfficiencyAnalysis\Month(2020, 12);
    $id = EfficiencyAnalysisId::next();
    $analysys = new \Domain\Model\EfficiencyAnalysis\EfficiencyAnalysis($id, $employee, $month);




    \LaravelDoctrine\ORM\Facades\EntityManager::persist($analysys);

    $analysys->addReview(
        AssessmentId::next(),new Check(new \DateTime('2020-12-01'), 0, 0), $criterion);

    \LaravelDoctrine\ORM\Facades\EntityManager::flush();


    return view('welcome');
});
