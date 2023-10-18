<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Budget;
use App\Services\BudgetService;
use Illuminate\Routing\Controller;
use App\Structures\Enum\AnalyticsPeriod;
use App\Http\Requests\BudgetCreateRequest;
use App\Http\Requests\BudgetUpdateRequest;
use App\Http\Requests\BudgetAnalyticsRequest;

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
     * Get analytics for a budget
     */
    public function analytics(Budget $budget, BudgetAnalyticsRequest $request)
    {
        $data = $request->validated();

        $periods = BudgetService::calculatePeriods(
            isset($data['start_time'])
                ? Carbon::parse($data['start_time'])
                : BudgetService::getStartDate($budget),
            isset($data['end_time'])
                ? Carbon::parse($data['end_time'])
                : null,
            isset($data['period'])
                ? AnalyticsPeriod::fromString($data['period'])
                : AnalyticsPeriod::All
        );

        $response = [];
        foreach ($periods as $period) {
            $response[] = BudgetService::analyticsForPeriod($budget, $period);
        }

        return $response;
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
     */
    public function delete(Budget $budget)
    {
        $budget->delete();

        return response()->noContent();
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
