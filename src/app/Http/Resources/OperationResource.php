<?php

namespace App\Http\Resources;

use App\Models\Operation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Operation $this */

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'amount' => $this->amount->getAmount()->toFloat(),
            'categories' => $this->relationLoaded('categories')
                ? $this->categories
                : null,
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),
            'budget_id' => $this->budget_id,
        ];
    }
}
