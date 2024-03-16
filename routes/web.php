<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\RentalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Rute untuk menampilkan halaman login
Route::get('/login', function () {
    return view('login');
})->name('login');

//auth
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/users', [AuthController::class, 'getAll'])->name('users.all');
Route::get('/users/role/user', [AuthController::class, 'getRoleUser'])->name('users.role.user');

//mobil
Route::get('/get-mobil', [MobilController::class, 'getAllMobil'])->name('mobil.index');
Route::get('/mobil/{id}', [MobilController::class, 'getMobilById'])->name('mobil.detail');
Route::post('/create-mobil', [MobilController::class, 'createMobil'])->name('mobil.create');
Route::patch('/update-mobil/{id}', [MobilController::class, 'updateMobil'])->name('mobil.update');
Route::delete('/delete-mobil/{id}', [MobilController::class, 'deleteMobil'])->name('mobil.delete');

//rental
Route::get('/get-rentals', [RentalController::class, 'getAllRentals'])->name('rental.index');
Route::get('/rentals/{id}', [RentalController::class, 'getRentalById'])->name('rental.detail');
Route::post('/create-rentals', [RentalController::class, 'createRental'])->name('rental.create');
Route::put('/update-rentals/{id}', [RentalController::class, 'updateRental'])->name('rental.update');
Route::delete('/delete-rentals/{id}', [RentalController::class, 'deleteRental'])->name('rental.delete');

Route::post('/return-car', [RentalController::class, 'returnCar'])->name('return.car');
Route::get('/completed-rentals', [RentalController::class, 'getCompletedRentals'])->name('completed.rentals');
Route::get('/ongoing-rentals', [RentalController::class, 'getOngoingRentals'])->name('ongoing.rentals');
