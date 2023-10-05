<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeCreateRequest;
use App\Http\Requests\IncomeUpdateRequest;
use App\Models\Budget;
use App\Models\Income;

class IncomeController extends Controller
{
    /**
     * Return all incomes for this budget, paginated, 100 per page.
     */
    public function incomes(Budget $budget)
    {
        return response()->json(
            $budget->incomes()->paginate(100)
        );
    }

    /**
     * Return a specific income by id
     */
    public function income(Budget $budget, Income $income)
    {
        return response()->json($income);
    }

    /**
     * Create a new income for a budget.
     * Returns newly created income.
     */
    public function create(Budget $budget, IncomeCreateRequest $request)
    {
        $data = $request->validated();

        $income = Income::create([
            'budget_id' => $budget->id,
            'user_id' => auth()->user()->id,
            ...$data,
        ]);

        return response()->json($income);
    }

    /**
     * Update a specific income
     */
    public function update(Budget $budget, Income $income, IncomeUpdateRequest $request)
    {
        $data = $request->validated();

        $income->update($data);

        return response()->json($income);
    }

    /**
     * Delete a specified income.
     * Returns deleted income object.
     */
    public function delete(Budget $budget, Income $income)
    {
        $income->delete();

        return response()->json($income);
    }
}
