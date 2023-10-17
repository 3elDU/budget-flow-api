<?php

namespace App\Structures\DTO;

use Illuminate\Support\Collection;

class AnalyticsDataDTO
{
    /**
     * @param AnalyticsPeriodDTO $period
     * @param Collection<float> $amounts
     */
    public function __construct(
        public AnalyticsPeriodDTO $period,
        public Collection $amounts,
    ) {
    }
}
