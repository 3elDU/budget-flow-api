<?php

namespace App\Structures\DTO;

use Illuminate\Support\Collection;

class AnalyticsDataDTO
{
    /**
     * @param AnalyticsPeriodDTO $period
     * @param Collection<Operation> $operations
     */
    public function __construct(
        public AnalyticsPeriodDTO $period,
        public Collection $operations,
    ) {
    }
}
