<?php

namespace App\Services;

use App\Structures\DTO\FiltersDTO;
use App\Structures\Enum\FilterType;
use App\Structures\DTO\FilterWhereDTO;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;

class FiltrationService
{
    public static function makeDTO(array $filters)
    {
        $dtos = array();

        foreach ($filters['filters'] as $filter) {
            if (is_null($filter['type'])) {
                throw new Exception('type must not be null');
            }
            $dtos[] = match ($filter['type']) {
                "where" => new FilterWhereDTO($filter['field'], $filter['operator'], $filter['value']),
                default => throw new Exception('unknown type')
            };
        }

        return new FiltersDTO(collect($dtos));
    }

    public static function performFiltration(Builder $query, FiltersDTO $filters)
    {
        foreach ($filters->filters as $filter) {
            match ($filter->type) {
                FilterType::Where => $query->where($filter->field, $filter->operator, $filter->value)
            };
        }
    }
}
