<?php

use Domain\Model\User\User;
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
    $repo = \LaravelDoctrine\ORM\Facades\EntityManager::getRepository(User::class);

    \LaravelDoctrine\ORM\Facades\EntityManager::persist(\Tests\Unit\Domain\Model\Builders\UserBuilder::aUser()->build());
    \LaravelDoctrine\ORM\Facades\EntityManager::flush();

    dd($repo->findAll());
    return view('welcome');
});
