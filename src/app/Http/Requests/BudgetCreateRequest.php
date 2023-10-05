<?php

namespace App\Http\Requests;

use App\Rules\CurrencyCode;
use App\Rules\HexColor;
use Illuminate\Foundation\Http\FormRequest;

class BudgetCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'required', 'min:1', 'max:255'],
            'description' => ['string', 'min:1', 'max:4096'],
            'color_hex' => ['string', 'required', new HexColor],
            'currency_iso' => ['string', 'required', new CurrencyCode]
        ];
    }
}
