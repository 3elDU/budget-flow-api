<?php

namespace App\Http\Controllers;

use App\Http\Requests\BudgetCreateRequest;
use App\Http\Requests\BudgetUpdateRequest;
use App\Models\Budget;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    /**
     * List all budgets associated with the user
     */
    public function budgets()
    {
        return response()->json(
            auth()->user()->budgets()->get()
        );
    }

    /**
     * Get a specific budget by id
     */
    public function budget(Budget $budget)
    {
        return response()->json($budget);
    }

    /**
     * Update a budget.
     * Returns updated budget object
     */
    public function update(Budget $budget, BudgetUpdateRequest $request)
    {
        $data = $request->validated();

        $budget->update($data);
        $budget->save();

        return response()->json($budget);
    }

    /**
     * Soft-delete a specific budget.
     * Returns deleted budget object
     */
    public function delete(Budget $budget)
    {
        $budget->delete();

        return response()->json($budget);
    }

    /**
     * Create a new budget.
     * Returns the newly created budget object
     */
    public function create(BudgetCreateRequest $request)
    {
        $data = $request->validated();

        $budget = Budget::create($data);
        $budget->users()->attach(auth()->user());

        return response()->json($budget);
    }
}
