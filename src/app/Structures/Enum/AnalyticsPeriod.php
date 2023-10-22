<?php

namespace App\Structures\Enum;

use Exception;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

enum AnalyticsPeriod: string
{
    case Day = 'day';
    case Week = 'week';
    case Month = 'month';
    case Quarter = 'quarter';
    case Year = 'year';
    case All = 'all';

    /**
     * @param string $period
     * @return AnalyticsPeriod
     * @throws Exception
     */
    public static function fromString(string $period): AnalyticsPeriod
    {
        foreach (self::cases() as $case) {
            if ($case->value == $period) {
                return $case;
            }
        }

        throw new Exception('unknown period');
    }

    /**
     * @param Carbon $start
     * @param Carbon|null $end
     * @return CarbonPeriod
     * @throws Exception
     */
    public function toCarbonPeriod(Carbon $start, Carbon|null $end): CarbonPeriod
    {
        return match ($this) {
            self::Day => CarbonPeriod::create($start, '1 day', $end),
            self::Week => CarbonPeriod::create($start, '1 week', $end),
            self::Month => CarbonPeriod::create($start, '1 month', $end),
            self::Quarter => CarbonPeriod::create($start, '1 quarter', $end),
            self::Year => CarbonPeriod::create($start, '1 year', $end),
            default => throw new Exception('invalid time period')
        };
    }
}
