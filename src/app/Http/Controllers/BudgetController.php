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
use App\Http\Resources\BudgetResource;
use App\Structures\Enum\AnalyticsPeriod;
use App\Http\Requests\BudgetAmountRequest;
use App\Http\Requests\BudgetRequest;
use App\Http\Requests\BudgetAnalyticsRequest;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @group Budget management
 *
 * Endpoints for managing budgets
 */
class BudgetController extends Controller
{
    /**
     * List all budgets associated with the user
     *
     * @return ResourceCollection<BudgetResource>
     * @apiResource App\Http\Resources\BudgetResource
     * @apiResourceModel App\Models\Budget
     */
    public function budgets(): ResourceCollection
    {
        /** @var User $user */
        $user = auth()->user();

        return BudgetResource::collection($user->budgets);
    }

    /**
     * Get a specific budget by id
     *
     * @param Budget $budget
     * @return BudgetResource
     * @apiResource App\Http\Resources\BudgetResource
     * @apiResourceModel App\Models\Budget
     */
    public function budget(Budget $budget): BudgetResource
    {
        return new BudgetResource($budget);
    }

    /**
     * Get analytics for a budget
     * @param Budget $budget
     * @param BudgetAnalyticsRequest $request
     * @return JsonResponse
     * @throws Exception
     * @response [
     *  {
     *      "period": [
     *          "2023-01-01T00:00:00.000000Z",
     *          "2023-01-08T00:00:00.000000Z"
     *      ],
     *      "expense": -2369.5,
     *      "average_expense": -59.24,
     *      "income": 2998.6,
     *      "average_income": 74.97,
     *      "budget_amount": 629.1
     *  }
     * ]
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
     * Get analytics for all budgets, combined
     * @param BudgetAnalyticsRequest $request
     * @return JsonResponse|Response
     * @throws Exception
     * @response [
     *  {
     *      "period": [
     *          "2023-01-01T00:00:00.000000Z",
     *          "2023-01-08T00:00:00.000000Z"
     *      ],
     *      "expense": -2369.5,
     *      "average_expense": -59.24,
     *      "income": 2998.6,
     *      "average_income": 74.97,
     *      "budget_amount": 629.1
     *  }
     * ]
     */
    public function analyticsAll(BudgetAnalyticsRequest $request): JsonResponse | Response
    {
        $data = $request->validated();
        /** @var User $user */
        $user = auth()->user();

        // Find the earliest start date from all budgets
        $budgets = $user->budgets()
            ->orderBy('created_at')
            ->get();
        if ($budgets->isEmpty()) {
            return response()->noContent(404);
        }

        $start_date = $budgets->firstOrFail()->created_at;

        $periods = BudgetService::calculatePeriods(
            isset($data['start_time'])
                ? Carbon::parse($data['start_time'])
                : $start_date,
            isset($data['end_time'])
                ? Carbon::parse($data['end_time'])
                : null,
            isset($data['period'])
                ? AnalyticsPeriod::fromString($data['period'])
                : AnalyticsPeriod::All
        );

        $response = [];

        foreach ($periods as $period) {
            $analytics = collect();
            foreach ($budgets as $budget) {
                $analytics->push(BudgetService::analyticsForPeriod($budget, $period));
            }

            $response[] = [
                'period' => [$period->start, $period->end],
                'expense' => round($analytics->sum('expense'), 2),
                'income' => round($analytics->sum('income'), 2),
                'average_expense' => round($analytics->average('average_expense'), 2),
                'average_income' => round($analytics->average('average_income'), 2),
                'budget_amount' => round($analytics->sum('budget_amount'), 2),
            ];
        }

        return response()->json($response);
    }

    /**
     * Returns budget amount at a specified date.
     * If no date specified, returns current budget balance.
     * @param Budget $budget
     * @param BudgetAmountRequest $request
     * @return float
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
     * @param Budget $budget
     * @param BudgetRequest $request
     * @return BudgetResource
     * @apiResource App\Http\Resources\BudgetResource
     * @apiResourceModel App\Models\Budget
     */
    public function update(Budget $budget, BudgetRequest $request): BudgetResource
    {
        $data = $request->validated();

        $budget->update($data);
        $budget->save();

        return new BudgetResource($budget);
    }

    /**
     * Soft-delete a specific budget.
     * @param Budget $budget
     * @return Response
     * @throws Exception
     * @response 204 {}
     */
    public function delete(Budget $budget): Response
    {
        $budget->delete();

        return response()->noContent();
    }

    /**
     * Create a new budget.
     * Returns the newly created budget object
     * @param BudgetRequest $request
     * @return BudgetResource
     * @apiResource App\Http\Resources\BudgetResource
     * @apiResourceModel App\Models\Budget
     */
    public function create(BudgetRequest $request): BudgetResource
    {
        $data = $request->validated();

        $budget = Budget::create($data);
        $budget->users()->attach(auth()->user());

        return new BudgetResource($budget);
    }
}
