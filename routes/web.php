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


//    $criterion = $repo->findById(new P);


//    $pharmacy = \Tests\Unit\Domain\Model\Builders\PharmacyBuilder::aPharmacy()->build();
    $ph = $repo->findById(new PharmacyId('0cb6fd00-476e-4780-99c0-d3e418bd991a'));

    dd($ph);
    //$repo->remove($criterion);

    return view('welcome');
});
