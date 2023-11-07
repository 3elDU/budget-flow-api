<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Services\BudgetService;
use Illuminate\Http\Resources\Json\JsonResource;

class BudgetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'currency_iso' => $this->currency_iso,
            'color_hex' => $this->color_hex,
            'balance' => BudgetService::budgetAmountAt($this->resource, null)
        ];
    }
}
