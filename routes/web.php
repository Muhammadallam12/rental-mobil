<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\UserController;
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
// Route::get('/login', function () {
//     return view('login');
// })->name('login');

//auth
Route::get('/', [AuthController::class, 'loginIndex'])->name('login.index');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/register-auth', [AuthController::class, 'store'])->name('register.store');
Route::get('/register', [AuthController::class, 'index'])->name('register');


Route::middleware(['auth'])->group(function () {
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/users', [AuthController::class, 'getAll'])->name('users.all');
Route::get('/users/role/user', [AuthController::class, 'getRoleUser'])->name('users.role.user');

//admin
Route::get('/dashboard-admin', [AdminController::class, 'index'])->name('admin.dashboard');
Route::resource('user', UserController::class);

// Rute untuk Mobil
Route::resource('mobil', MobilController::class);

// Rute untuk Rental
Route::resource('rental', RentalController::class);

Route::post('/return-car', [RentalController::class, 'returnCar'])->name('return.car');
Route::get('/completed-rentals', [RentalController::class, 'getCompletedRentals'])->name('completed.rentals');
Route::get('/ongoing-rentals', [RentalController::class, 'getOngoingRentals'])->name('ongoing.rentals');
});
