<?php

namespace App\Structures\DTO;

use App\Structures\Enum\AnalyticsPeriod;
use Carbon\Carbon;
use DateTime;

class AnalyticsPeriodDTO
{
    /**
     * @param Carbon $start
     * @param Carbon $end
     * @param AnalyticsPeriod $period
     */
    public function __construct(
        public Carbon $start,
        public Carbon $end,
        public AnalyticsPeriod $period,
    ) {
    }
}
