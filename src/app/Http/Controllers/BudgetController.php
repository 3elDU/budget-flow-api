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
        /** @var \App\Models\User */
        $user = auth()->user();
        return response()->json(
            $user->budgets()->get()
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
        $request = $request->validated();

        if (array_key_exists('name', $request)) {
            $budget->name = $request['name'];
        }
        if (array_key_exists('description', $request)) {
            $budget->description = $request['description'];
        }

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
        $request = $request->validated();
        $budget = Budget::create($request);
        $budget->users()->attach(auth()->user());
        return response()->json($budget);
    }
}
