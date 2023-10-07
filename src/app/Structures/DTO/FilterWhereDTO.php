<?php

namespace App\Structures\DTO;

use App\Structures\Enum\FilterType;

class FilterWhereDTO
{
    public $type = FilterType::Where;

    public function __construct(
        public string $field,
        public string $operator,
        public mixed $value,
    ) {
    }
}
