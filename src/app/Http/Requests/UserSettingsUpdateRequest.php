<?php

namespace App\Http\Requests;

use App\Rules\CurrencyCode;
use Illuminate\Foundation\Http\FormRequest;

class UserSettingsUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'preferred_currency_iso' => ['string', 'nullable', new CurrencyCode],
            'show_fractional' => ['boolean']
        ];
    }
}
