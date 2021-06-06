<?php

use Doctrine\ORM\EntityManagerInterface;
use Domain\Model\Employee\EmployeeRepository;
use Domain\Model\Pharmacy\PharmacyId;
use Domain\Model\User\User;
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
    $repo = app()->make(\Domain\Model\User\UserRepository::class);


    $user = \Tests\Unit\Domain\Model\Builders\UserBuilder::aUser()->build();
    $repo->add($user);
    $em->flush();
    return view('welcome');
});
