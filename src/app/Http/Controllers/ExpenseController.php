<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseCreateRequest;
use App\Http\Requests\ExpenseUpdateRequest;
use App\Models\Budget;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Return all expenses for this budget, paginated, 100 per page.
     */
    public function expenses(Budget $budget)
    {
        return response()->json(
            $budget->expenses()->paginate(100)
        );
    }

    /**
     * Return a specific expense by id
     */
    public function expense(Budget $budget, Expense $expense)
    {
        return response()->json($expense);
    }

    /**
     * Create a new expense for a budget.
     * Returns newly created expense.
     */
    public function create(Budget $budget, ExpenseCreateRequest $request)
    {
        $data = $request->validated();

        $expense = Expense::create([
            'budget_id' => $budget->id,
            'user_id' => auth()->user()->id,
            ...$data,
        ]);

        return response()->json($expense);
    }

    /**
     * Update a specific income
     */
    public function update(Budget $budget, Expense $expense, ExpenseUpdateRequest $request)
    {
        $data = $request->validated();

        $expense->update($data);

        return response()->json($expense);
    }

    /**
     * Delete a specified expense.
     * Returns deleted expense object.
     */
    public function delete(Budget $budget, Expense $expense)
    {
        $expense->delete();

        return response()->json($expense);
    }
}
