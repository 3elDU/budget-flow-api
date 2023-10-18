<?php

namespace App\Structures\DTO;

use Carbon\Carbon;
use App\Structures\Enum\AnalyticsPeriod;

class AnalyticsPeriodDTO
{
    /**
     * @param Carbon $start
     * @param Carbon|null $end
     * @param AnalyticsPeriod $period
     */
    public function __construct(
        public Carbon $start,
        public Carbon|null $end,
        public AnalyticsPeriod $period,
    ) {
    }
}
