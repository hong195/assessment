<?php

use Doctrine\ORM\EntityManagerInterface;
use Domain\Model\Employee\EmployeeRepository;
use Domain\Model\Pharmacy\PharmacyId;
use Illuminate\Support\Facades\Hash;
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
    $em = app()->make(EntityManagerInterface::class);
    $repo = app()->make(\Domain\Model\Pharmacy\PharmacyRepository::class);

    $pharmacy = $repo->findById(new PharmacyId('6a973177-bb96-4e01-93dd-90f2c43a999b'));

    $str = Hash::make(34343);
    dd($str);
//    $employee = \Tests\Unit\Domain\Model\Builders\EmployeeBuilder::anEmployee()->withPharmacy($pharmacy)->build();
//
//    $pharmacy->addEmployee($employee);
//    $repo->add($pharmacy);
//
//    $em->flush();

    dd($pharmacy->getEmployees()->toArray());
    return view('welcome');
});
