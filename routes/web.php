<?php

use App\Notifications\FinalGradeCompleted;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Model\Employee\EmployeeRepository;
use App\Domain\Model\Pharmacy\PharmacyId;
use App\Domain\Model\User\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;

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
    return view('admin');
});

Route::get('/email', function () {
    $employeeRepository = app()->make(EmployeeRepository::class);
    $finalGradeRepository = app()->make(\App\Infrastructure\Services\FinalGradesQuery::class);
    $employee = $employeeRepository->find('0de88675-bec5-44ae-84cb-755f36302123');
    $pharmacy  = $employee->getPharmacy();

    $finalGrades = $finalGradeRepository
        ->byPharmacies([(string) $pharmacy->getId()])
        ->execute();

    $employees = $pharmacy->getEmployees()->toArray();

    Notification::route('mail', ['alexeyhong10@gmail.com'])
        ->notify(new FinalGradeCompleted($finalGrades, $employees));
});
