<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Budget;
use Illuminate\Http\Response;
use App\Services\BudgetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Structures\Enum\AnalyticsPeriod;
use App\Http\Requests\BudgetAmountRequest;
use App\Http\Requests\BudgetCreateRequest;
use App\Http\Requests\BudgetUpdateRequest;
use App\Http\Requests\BudgetAnalyticsRequest;

/**
 * @group Budget management
 *
 * Endpoints for managing budgets
 */
class BudgetController extends Controller
{
    /**
     * List all budgets associated with the user
     */
    public function budgets(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        return response()->json($user->budgets);
    }

    /**
     * Get a specific budget by id
     */
    public function budget(Budget $budget): JsonResponse
    {
        return response()->json($budget);
    }

    /**
     * Get analytics for a budget
     * @throws Exception
     */
    public function analytics(Budget $budget, BudgetAnalyticsRequest $request): JsonResponse
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

        return response()->json($response);
    }

    /**
     * Returns budget amount at a specified date.
     * If no date specified, returns current budget balance.
     */
    public function amount(Budget $budget, BudgetAmountRequest $request): float
    {
        $data = $request->validated();

        return BudgetService::budgetAmountAt(
            $budget,
            isset($data['date'])
                ? Carbon::parse($data['date'])
                : now()
        );
    }

    /**
     * Update a budget.
     * Returns updated budget object
     */
    public function update(Budget $budget, BudgetUpdateRequest $request): JsonResponse
    {
        $data = $request->validated();

        $budget->update($data);
        $budget->save();

        return response()->json($budget);
    }

    /**
     * Soft-delete a specific budget.
     */
    public function delete(Budget $budget): Response
    {
        $budget->delete();

        return response()->noContent();
    }

    /**
     * Create a new budget.
     * Returns the newly created budget object
     */
    public function create(BudgetCreateRequest $request): JsonResponse
    {
        $data = $request->validated();

        $budget = Budget::create($data);
        $budget->users()->attach(auth()->user());

        return response()->json($budget);
    }
}
