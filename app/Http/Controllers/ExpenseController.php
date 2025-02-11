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

    public function getByUserId(Request $request) {
        return Expense::where('userID', $request->user()->id)->get();
    }

    public function updatePaid(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);

        $expense->paid = $request->has('paid') ? true : false;
        $expense->save();

        return redirect()->route('dashboard');
    }

    public function delete($id) {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return redirect()->route('dashboard')->with('message', 'Expense deleted successfully!');
    }

    public function apiInsert(Request $request) {
        if (!$request->user()) {
            return response()->json(['error' => 'No autenticado.'], 401);
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'quantity' => 'required|regex:/^\d+(\.\d{1,2})?$/|min:0.01',
            'date' => 'required|date',
            'paid' => 'nullable|boolean',
        ]);

        $user = $request->user();

        $expense = new Expense();
        $expense->subject = $validated['subject'];
        $expense->quantity = $validated['quantity'];
        $expense->paid = $validated['paid'];
        $expense->date = $validated['date'];
        $expense->userID = $user->id;
        $expense->save();

        return response()->json([
            'message' => 'Gasto registrado con éxito',
            'expense' => $expense
        ], 201);
    }

    public function apiDeleteExpense($id) {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return response()->json([
            'message' => 'Expense deleted successfully'
        ], 201);
    }

    public function apiUpdateExpense(Request $request) {
        $expense = Expense::findOrFail($request->id);

        $expense->paid = $request->paid;
        $expense->save();

        return response()->json([
            'message' => 'Expense updated successfully',
            'expense' => $expense
        ], 201);
    }
}
