<?php

namespace App\Structures\DTO;

use Brick\Money\Money;
use Illuminate\Support\Collection;

class AnalyticsDataDTO
{
    /**
     * @param AnalyticsPeriodDTO $period
     * @param Collection<Money> $amounts
     */
    public function __construct(
        public AnalyticsPeriodDTO $period,
        public Collection $amounts,
    ) {
    }
}
