<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FiltersRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'filters' => ['array', 'nullable'],
            'filters.*.type' => ['string', 'required'],
            'filters.*.field' => ['string', 'required'],
            'filters.*.operator' => ['string', 'required'],
            'filters.*.value' => ['nullable', 'required'],
            ...(new PaginatorRequest())->rules(),
        ];
    }
}
