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

Route::middleware('auth:sanctum')->get('/expenses', [ExpenseController::class, 'get']);

Route::middleware('auth:sanctum')->post('/create', [ExpenseController::class, 'apiInsert']);
