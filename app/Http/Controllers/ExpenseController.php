<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Exception;
use Illuminate\Http\Request;

class ExpenseController extends Controller {
    public function insert(Request $request) {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/|min:0.01',
            'date' => 'required|date',
            'paid' => 'nullable|boolean',
        ]);

        $expense = new Expense();
        $expense->subject = $request['subject'];
        $expense->price = $request['price'];
        $expense->paid = $request->has('paid') ? 1 : 0;
        $expense->date = $request['date'];
        $expense->userID = auth()->id();
        $expense->save();

        return redirect()->back()->with('success', 'Gasto registrado con éxito.');
    }

    public function get() {
        return Expense::where('userID', auth()->id());
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

    /** Returns all expenses by user */
    public function getExpensesByUser(Request $request) {
        return Expense::where('userID', $request->user()->id)->get();
    }

    /** Returns an expenses list filtered by if its paid or not
     *
     * Example route:
     *
     * http://localhost/api/1 -> Returns all paid expenses
     *
     * http://localhost/api/0 -> Returns all non-paid expenses
     * */
    public function getFilteredExpenses(Request $request, $paid){
        return Expense::where('userID', $request->user()->id)->where('paid', $paid)->get();
    }

    /** Creating an expense
     *
     * Example body:
     *
     * ```
     * {
     *    "subject": "Red Bull x 3",
     *    "price": "4",
     *    "date": "2025-02-07",
     *    "paid": false
     * }
     * ```
     * */
    public function insertExpense(Request $request) {
        if (!$request->user()) {
            return response()->json(['error' => 'No autenticado.'], 401);
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/|min:0.01',
            'date' => 'required|date',
            'paid' => 'nullable|boolean',
        ]);

        $user = $request->user();

        $expense = new Expense();
        $expense->subject = $validated['subject'];
        $expense->price = $validated['price'];
        $expense->paid = $validated['paid'];
        $expense->date = $validated['date'];
        $expense->userID = $user->id;
        $expense->save();

        return response()->json([
            'message' => 'Gasto registrado con éxito',
            'expense' => $expense
        ], 201);
    }

    /**
     * Deleting an expenses, indicating the id of the expenses on the route
     *
     * Example: http://localhost/api/delete/1
     */
    public function deleteExpense($id) {
        try {
            $expense = Expense::findOrFail($id);
            $expense->delete();

            return response()->json([
                'message' => 'Expense deleted successfully'
            ]);
        } catch (Exception) {
            return response()->json(['message' => 'Expense not found'], 404);
        }
    }

    /** Updating an expense
     *
     * Example body:
     * ```
     * {
     *    "id": 1,
     *    "paid": 1 // Boolean value (1: true or 0: false)
     * }
     * ```
     */
    public function updateExpense(Request $request) {
        try {
            $expense = Expense::findOrFail($request->id);

            $expense->paid = $request->paid;
            $expense->save();

            return response()->json([
                'message' => 'Expense updated successfully',
                'expense' => $expense
            ]);
        } catch (Exception) {
            return response()->json(['message' => 'Expense not found'], 404);
        }
    }
}
