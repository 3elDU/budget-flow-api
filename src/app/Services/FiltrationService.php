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
                'where', 'whereIn' => new FilterWhereDTO($filter['field'], $filter['operator'], $filter['value']),
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
            $field = $filter->field;
            $operator = $filter->operator;

            if (in_array($operator, ['like', 'not like', 'ilike', 'not ilike'])) {
                $value = "%$filter->value%";
            } else {
                $value = $filter->value;
            }

            if (str_contains($field, '.')) {
                [$relation, $field] = explode('.', $field);

                $query->whereHas($relation, fn (Builder $query) => self::applyQuery(
                    $query,
                    $filter->type,
                    $field,
                    $operator,
                    $value,
                ));
            } else {
                self::applyQuery($query, $filter->type, $field, $operator, $value);
            }
        }
    }

    private static function applyQuery(
        Builder $query,
        FilterType $type,
        string $field,
        string $operator,
        mixed $value,
    ): Builder
    {
        match ($type) {
            FilterType::Where => $query->where($field, $operator, $value),
            FilterType::WhereIn => $query->whereIn($field, $operator, $value),
        };

        return $query;
    }
}
