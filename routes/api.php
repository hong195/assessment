<?php

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    die;
    $credentials = request(['login', 'password']);

    dd($credentials);
    $credentials = [
        'login.login' => $request->login,
        'password' => $request->password
    ];


    if (! $token = auth()->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    dd(auth()->user(), $token);
    return ['user' => auth()->user(), 'token' => $token];
});
