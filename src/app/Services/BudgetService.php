<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Budget;
use App\Models\Operation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use App\Structures\DTO\AnalyticsDataDTO;
use App\Structures\Enum\AnalyticsPeriod;
use App\Structures\DTO\AnalyticsPeriodDTO;
use Illuminate\Database\Eloquent\RelationNotFoundException;

class BudgetService
{
    /**
     * From the given request, return a collection of periods for calculating analytics
     * @param array $request
     * @return Collection<AnalyticsPeriodDTO>
     */
    public static function calculatePeriods(Carbon $start, Carbon|null $end, AnalyticsPeriod $period): Collection
    {
        if ($period == AnalyticsPeriod::All) {
            // If the request is to get analytics over the whole period,
            // create a single period spanning from the start to the end
            return collect(
                new AnalyticsPeriodDTO($start, $end, $period)
            );
        }

        /** @var AnalyticsPeriodDTO[] $periods */
        $periods = [];
        $carbonPeriod = $period->toCarbonPeriod($start, $end ?? now());

        /** @var Carbon $previousDate */
        $previousDate = $start;
        // Fill the periods array
        foreach ($carbonPeriod as $date) {
            if ($date == $previousDate) {
                continue;
            }

            $periods[] = new AnalyticsPeriodDTO($previousDate, $date, $period);
            $previousDate = $date;
        }

        // If the periods array is empty,
        // add a single period spanning from start to end
        if (sizeof($periods) == 0) {
            $periods[] = new AnalyticsPeriodDTO(
                $start,
                $end,
                $period
            );
        } else if ($previousDate != $end) {
            // If the periods array doesn't include the end date, append a new period
            // spanning from the last date in the periods array to the end date
            $periods[] = new AnalyticsPeriodDTO(
                $periods[array_key_last($periods)]->end,
                $end,
                $period
            );
        }

        return collect($periods);
    }

    /**
     * Returns analytics object for the specified period
     * @param Budget $budget
     * @param AnalyticsPeriodDTO $period
     * @return array
     * @throws RelationNotFoundException
     */
    public static function analyticsForPeriod(Budget $budget, AnalyticsPeriodDTO $period)
    {
        $operations = BudgetService::operations($budget, $period);
        if ($operations->amounts->count() == 0) {
            return [
                'period' => [$period->start, $period->end],
                'expense' => 0,
                'average_expense' => 0,
                'income' => 0,
                'average_income' => 0,
                'budget_amount' => BudgetService::budgetAmountAt($budget, $period->end)
            ];
        }

        // Calculate total money spent/earned over the specified period
        $expensePerPeriod = 0.0;
        $incomePerPeriod = 0.0;

        foreach ($operations->amounts as $amount) {
            if ($amount < 0) {
                $expensePerPeriod += -$amount;
            } else {
                $incomePerPeriod += $amount;
            }
        }

        // Count the number of expenses during this period
        $expenseCount = $operations->amounts
            ->filter(fn ($amount) => $amount < 0.0)
            ->count();

        $avgExpensePerPeriod = 0.0;
        if ($expenseCount != 0) {
            // Compute the average amount of money spent over all expenses from this period
            $avgExpensePerPeriod = $expensePerPeriod / $expenseCount;
        }

        // Count the number of incomes during this period
        $incomeCount = $operations->amounts
            ->filter(fn ($amount) => $amount > 0.0)
            ->count();

        $avgIncomePerPeriod = 0.0;
        if ($incomeCount != 0) {
            $avgIncomePerPeriod = $incomePerPeriod / $incomeCount;
        }

        return [
            'period' => [$period->start, $period->end],
            'expense' => $expensePerPeriod,
            'average_expense' => $avgExpensePerPeriod,
            'income' => $incomePerPeriod,
            'average_income' => $avgIncomePerPeriod,
            'budget_amount' => BudgetService::budgetAmountAt($budget, $period->end)
        ];
    }

    /**
     * Make a DTO with the total sum of all operations in the time range,
     * along with array of amounts for each operation
     * @param DateTime $start
     * @param DateTime $end
     * @return AnalyticsDataDTO
     */
    public static function operations(Budget $budget, AnalyticsPeriodDTO $period)
    {
        $getOperationsFn = function () use ($budget, $period) {
            $operations = Operation::query()
                ->whereBelongsTo($budget)
                ->whereBetween('created_at', [$period->start, $period->end ?? now()])
                ->get();

            $amounts = array();

            foreach ($operations as $operation) {
                $amounts[] = $operation->amount;
            }

            return json_encode($amounts);
        };

        // Don't cache the operations if the end time isn't determined,
        // or if the period is set to All
        if ($period->period == AnalyticsPeriod::All || is_null($period->end)) {
            $amounts = json_decode($getOperationsFn());
        } else {
            $amounts = json_decode(
                Cache::tags(["budget:{$budget->id}", 'operations', "start_time:{$period->start}", "end_time:{$period->end}"])
                    ->rememberForever(
                        "{$period->start}, {$period->end}",
                        $getOperationsFn
                    )
            );
        }

        return new AnalyticsDataDTO(
            $period,
            collect($amounts),
        );
    }

    /**
     * Returns how much money there is on a budget, on a given time
     * @param Budget $budget
     * @param DateTime|null $time
     * @return float
     */
    public static function budgetAmountAt(Budget $budget, Carbon|null $time)
    {
        $calculateBudgetAmountFn = function () use ($budget, $time) {
            $operations = Operation::query()
                ->whereBelongsTo($budget)
                ->where('created_at', '<=', $time ?? now())
                ->get();

            $money = 0.0;

            foreach ($operations as $operation) {
                $money += $operation->amount;
            }

            return $money;
        };

        // Don't cache the budget amount if the time isn't specified
        if (is_null($time)) {
            return $calculateBudgetAmountFn();
        }

        return floatval(Cache::tags(["budget:{$budget->id}", 'amount_at'])
            ->rememberForever($time, $calculateBudgetAmountFn));
    }

    /**
     * Returns the 'starting' date for a budget, e.g. when the first operation in that budget
     * was created
     * @param Budget $budget
     * @return Carbon
     */
    public static function getStartDate(Budget $budget)
    {
        /** @var \Carbon\Carbon $date */
        $date = Operation::query()
            ->whereBelongsTo($budget)
            ->oldest()
            ->firstOrFail()
            ->created_at;
        // Set time to 00:00
        $date->setTime(0, 0);

        return $date;
    }
}