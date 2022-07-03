<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CharactersController;
use App\Http\Controllers\ColaboratorsController;

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
Route::get('/colaborators', [ColaboratorsController::class,'index']);
Route::get('/colaborators/{name?}', [ColaboratorsController::class,'show']);

Route::get('/characters', [CharactersController::class,'index']);
Route::get('/characters/{name?}', [CharactersController::class,'getData']);
/* Route::resource('/characters', CharactersController::class); */


