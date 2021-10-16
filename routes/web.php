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
    Notification::route('mail', ['alexeyhong10@gmail.com'])
        ->notify(new FinalGradeCompleted([]));
});
