<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ExpenseController;

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    // Elimina tokens anteriores (opcional)
    $user->tokens()->delete();

    // Crea un nuevo token
    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => $user
    ]);
});


Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6'
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password)
    ]);

    return response()->json([
        'message' => 'Usuario creado correctamente',
        'user' => $user
    ], 201);
});

/** Getting all expenses by user */
Route::middleware('auth:sanctum')->get('/expenses', [ExpenseController::class, 'getByUserId']);

/** Creating an expenses */
Route::middleware('auth:sanctum')->post('/create', [ExpenseController::class, 'apiInsert']);

/**
 * Deleting an expenses, indicating the id of the expenses on the route
 * Example: http://localhost/api/delete/1
 */
Route::middleware('auth:sanctum')->delete('/delete/{id}', [ExpenseController::class, 'apiDeleteExpense']);

/** Updating an expense
 * Example body:
 * {
 *    "id": 1,
 *    "paid": 1 // Boolean value (1: true or 0: false)
 * }
 */
Route::middleware('auth:sanctum')->put('/update', [ExpenseController::class, 'apiUpdateExpense']);
