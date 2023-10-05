<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseCreateRequest extends FormRequest
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
            'description' => ['string', 'nullable', 'min:1', 'max:4096'],
            'amount' => ['decimal:0,2', 'required', 'min:0.01']
        ];
    }
}
