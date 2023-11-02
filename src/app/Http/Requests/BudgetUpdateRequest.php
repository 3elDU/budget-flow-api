<?php

namespace App\Http\Requests;

use App\Rules\HexColor;
use Illuminate\Foundation\Http\FormRequest;

class BudgetUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'min:1', 'max:255'],
            'description' => ['string', 'nullable', 'min:1', 'max:4095'],
            'color_hex' => ['string', new HexColor],
        ];
    }
}
