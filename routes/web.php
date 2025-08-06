<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WeekController;
use Illuminate\Support\Facades\Route;
use App\Exports\LoansExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Loan;


Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', function () {
    return view('auth.login');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/loans/export', function () {
    $loans = Loan::with(['client.user'])->get(); // Obtener todos los préstamos con relaciones
    return Excel::download(new LoansExport($loans), 'reporte_prestamos.xlsx');
})->name('loans.export');
Route::middleware(['auth'])->group(function () {
    Route::resource('clients', ClientController::class);
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
    Route::get('/clients/{client}/loans', [LoanController::class, 'indexByClient'])->name('clients.loans');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    // Ver semanas de un préstamo
    Route::get('/loans/{loan}/weeks', [LoanController::class, 'weeks'])->name('loans.weeks');

    // Marcar como pagado
    Route::post('/weeks/{week}/pagar', [WeekController::class, 'pagar'])->name('weeks.pagar');
    // Editar semana (admin)
    Route::get('/weeks/{week}/edit', [WeekController::class, 'edit'])->name('weeks.edit');
    Route::put('/weeks/{week}', [WeekController::class, 'update'])->name('weeks.update');

    Route::get('/weeks/{week}/abonar', [WeekController::class, 'showAbonoForm'])->name('weeks.abonoForm');
    Route::post('/weeks/{week}/abonar', [WeekController::class, 'procesarAbono'])->name('weeks.procesarAbono');

    Route::get('/reporte-prestamos', [LoanController::class, 'reporte'])->name('reporte.prestamos');





});

require __DIR__.'/auth.php';
