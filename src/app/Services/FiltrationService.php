<?php

namespace App\Services;

use Exception;
use App\Structures\DTO\FiltersDTO;
use Illuminate\Support\Collection;
use App\Structures\Enum\FilterType;
use App\Structures\DTO\FilterWhereDTO;
use Illuminate\Contracts\Database\Eloquent\Builder;

class FiltrationService
{
    /**
     * @param array $filters
     * @return FiltersDTO
     * @throws Exception
     */
    public static function makeDTO(array $filters): FiltersDTO
    {
        $dtos = [];

        // Return an empty collection if 'filters' property is not set
        if (!isset($filters['filters']) || is_null($filters['filters'])) {
            return new FiltersDTO(new Collection());
        }

        foreach ($filters['filters'] as $filter) {
            if (is_null($filter['type'])) {
                throw new Exception('type must not be null');
            }
            $dtos[] = match ($filter['type']) {
                'where' => new FilterWhereDTO($filter['field'], $filter['operator'], $filter['value']),
                default => throw new Exception('Unknown type'),
            };
        }

        return new FiltersDTO(collect($dtos));
    }

    /**
     * @param Builder $query
     * @param FiltersDTO $filters
     * @return void
     * @throws Exception
     */
    public static function performFiltration(Builder $query, FiltersDTO $filters): void
    {
        foreach ($filters->filters as $filter) {
            match ($filter->type) {
                FilterType::Where => $query->where($filter->field, $filter->operator, $filter->value),
                default => throw new Exception('Unknown type'),
            };
        }
    }
}
