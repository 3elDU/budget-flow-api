<?php

namespace App\Structures\DTO;

use Illuminate\Support\Collection;

class FiltersDTO
{
    /**
     * @param Collection<FilterWhereDTO> $filters
     */
    public function __construct(
        public Collection $filters
    ) {
    }
}
