<?php

namespace Tests\Feature;

use App\Models\Budget;
use App\Services\BudgetService;
use App\Structures\Enum\AnalyticsPeriod;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class BudgetServiceBenchmarkTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        Benchmark::dd(function() {
            Cache::flush();

            $budget = Budget::first();

            $data = [
                'start_time' => '01.01.2023',
                'end_time' => '31.12.2023',
                'period' => 'week',
            ];

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

            response()->json($response);
        }, 10);
    }
}
