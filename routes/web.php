<?php

use Doctrine\ORM\EntityManagerInterface;
use Domain\Model\Pharmacy\PharmacyId;
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

    $pharmacy = \Tests\Unit\Domain\Model\Builders\PharmacyBuilder::aPharmacy()->build();

    $employee = \Tests\Unit\Domain\Model\Builders\EmployeeBuilder::anEmployee()
            ->withPharmacy($pharmacy)->build();

    $pharmacy->addEmployee($employee);
    $pharmacy->resign($employee);
    $repo->add($pharmacy);
    $em->flush();

    return view('welcome');
});
