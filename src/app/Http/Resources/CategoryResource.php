<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Category $this */

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'color_hex' => $this->color_hex,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
