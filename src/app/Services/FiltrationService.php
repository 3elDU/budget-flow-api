<?php

namespace App\Services;

use App\Structures\DTO\FilterDTO;
use App\Structures\Enum\FilterType;
use App\Structures\DTO\FilterWhereDTO;
use Illuminate\Contracts\Database\Eloquent\Builder;

class FiltrationService
{
    public static function makeDTO(array $filters)
    {
        $dtos = array();

        foreach ($filters['filters'] as $filter) {
            $dtos[] = match ($filter['type']) {
                "where" => new FilterWhereDTO($filter['field'], $filter['operator'], $filter['value'])
            };
        }

        return new FilterDTO(collect($dtos));
    }

    public static function performFiltration(Builder $query, FilterDTO $filters)
    {
        foreach ($filters->filters as $filter) {
            match ($filter->type) {
                FilterType::Where => $query->where($filter->field, $filter->operator, $filter->value)
            };
        }
    }
}
