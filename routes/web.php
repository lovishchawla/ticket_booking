<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeatController;

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
    return view('welcome');
});

Route::get('/seats', [SeatController::class, 'index'])->name('seats.index');
Route::post('/book/seats', [SeatController::class, 'bookSeats'])->name('seats.book');
