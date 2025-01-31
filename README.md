# Expense Tracker Documentation

## Introduction
This project is a web application built with Laravel, using Sail and Docker for development. It allows users to track their expenses, mark them as paid or unpaid, and see an overview of their spending. User authentication and account management are handled through Laravel Breeze.

## Features
- User authentication via accounts.
- Register an expense with a subject, amount, date, and payment status.
- Mark expenses as paid or unpaid.
- View total expenses and remaining unpaid expenses.
- Delete expenses.
- Profile management including updates and account deletion.

## Technologies Used
- **Laravel 10** (with Sail for Docker-based development)
- **Laravel Breeze** (for authentication)
- **TailwindCSS** (for styling)
- **SQLite** (for database storage)

---

## Installation

### Prerequisites
Make sure you have the following installed:
- Docker & Docker Compose
- PHP 8.0+
- Composer

## Application Structure

### **Routes**
The main routes for expenses are defined in `web.php`:
```php
Route::middleware(['auth'])->group(function () {
    Route::post('/expense/insert', [ExpenseController::class, 'insert'])->name('expense.insert');
    Route::put('/expense/update-paid/{id}', [ExpenseController::class, 'updatePaid'])->name('expense.updatePaid');
    Route::delete('/expense/delete/{id}', [ExpenseController::class, 'delete'])->name('expense.delete');
});
```

### **Controllers**

#### **ExpenseController**
Handles expense operations (CRUD):
```php
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

        return redirect()->back()->with('success', 'Expense registered successfully.');
    }

    public function get() {
        return Expense::all();
    }

    public function updatePaid(Request $request, $id) {
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
}
```

#### **ProfileController**
Handles user profile operations:
```php
namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller {
    public function edit(Request $request): View {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
```

---

## Deployment Process
To deploy the application, follow these steps:

1. Navigate to the project directory:
   ```sh
   cd locationProject
   ```

2. Start Laravel Sail in detached mode:
   ```sh
   ./vendor/bin/sail up -d
   ```

3. Run the frontend assets:
   ```sh
   ./vendor/bin/sail npm run dev
   ```

4. Access the application at:
   ```
   http://localhost
   ```

---

## Conclusion
This Laravel-based Expense Tracker allows users to track their expenses efficiently with authentication and an intuitive UI. By leveraging Laravel Sail and Breeze, the setup is simple and scalable.
