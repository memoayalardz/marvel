<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CharactersController;
use App\Http\Controllers\ColaboratorsController;

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

Route::get('/marvel', function () {
    return view('welcome');
});
Route::get('/marvel/colaborators/{name?}', [ColaboratorsController::class,'show']);
Route::get('/marvel/characters/{name?}', [CharactersController::class,'show']); 
