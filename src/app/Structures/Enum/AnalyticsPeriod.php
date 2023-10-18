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

    public static function fromString(string $period)
    {
        foreach (self::cases() as $case) {
            if ($case->value == $period) {
                return $case;
            }
        }

        throw new Exception('unknown period');
    }

    public function toCarbonPeriod(Carbon $start, Carbon|null $end): CarbonPeriod
    {
        return match ($this) {
            $this::Day => CarbonPeriod::create($start, '1 day', $end),
            $this::Week => CarbonPeriod::create($start, '1 week', $end),
            $this::Month => CarbonPeriod::create($start, '1 month', $end),
            $this::Quarter => CarbonPeriod::create($start, '1 quarter', $end),
            $this::Year => CarbonPeriod::create($start, '1 year', $end),
            default => throw new Exception('invalid time period')
        };
    }
}
