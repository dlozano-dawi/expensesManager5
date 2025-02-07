<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller {
    public function insert(Request $request) {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'quantity' => 'required|regex:/^\d+(\.\d{1,2})?$/|min:0.01',
            'date' => 'required|date',
            'paid' => 'nullable|boolean',
        ]);

        $expense = new Expense();
        $expense->subject = $request['subject'];
        $expense->quantity = $request['quantity'];
        $expense->paid = $request->has('paid') ? 1 : 0;
        $expense->date = $request['date'];
        $expense->userID = auth()->id();
        $expense->save();

        return redirect()->back()->with('success', 'Gasto registrado con éxito.');
    }

    public function get() {
        return Expense::all();
    }

    public function updatePaid(Request $request, $id)
    {
        // Buscar el gasto por ID
        $expense = Expense::findOrFail($id);

        // Actualizar el campo 'paid' en la base de datos
        $expense->paid = $request->has('paid') ? true : false;
        $expense->save();

        // Retornar la misma página con los gastos actualizados
        return redirect()->route('dashboard');
    }

    public function delete($id)
    {
        // Buscar el gasto por ID y eliminarlo
        $expense = Expense::findOrFail($id);
        $expense->delete();

        // Redirigir a la página de dashboard después de eliminar el gasto
        return redirect()->route('dashboard')->with('message', 'Expense deleted successfully!');
    }

    public function apiInsert(Request $request) {

        // Validar la solicitud
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'quantity' => 'required|regex:/^\d+(\.\d{1,2})?$/|min:0.01',
            'date' => 'required|date',
            'paid' => 'nullable|boolean',
        ]);

        // Obtener el usuario autenticado
        $user = $request->user(); // Equivalente a auth()->user()

        // Crear la instancia de Expense
        $expense = new Expense();
        $expense->subject = $validated['subject'];
        $expense->quantity = $validated['quantity'];
        $expense->paid = $request->has('paid') ? 1 : 0;
        $expense->date = $validated['date'];
        $expense->userID = $user->id; // Asignar el ID del dueño del token
        $expense->save();

        // Devolver una respuesta JSON
        return response()->json([
            'message' => 'Gasto registrado con éxito',
            'expense' => $expense
        ], 201);
    }

}
