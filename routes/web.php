<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use App\Models\Expense;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $expenses = Expense::where('userID', auth()->id())->get();

    return view('dashboard', compact('expenses'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/expense', [ExpenseController::class, 'insert'])->name('expense.insert');
Route::get('/expense', [ExpenseController::class, 'get'])->name('expense.get');
Route::put('/expense/{expense}/updatePaid', [ExpenseController::class, 'updatePaid'])->name('expense.updatePaid');
Route::delete('/expense/{expense}/delete', [ExpenseController::class, 'delete'])->name('expense.delete');

require __DIR__.'/auth.php';
