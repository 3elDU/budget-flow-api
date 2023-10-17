<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BudgetAnalyticsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_time' => ['string', 'nullable', 'date'],
            'end_time' => ['string', 'nullable', 'date'],
            'period' => ['string', 'nullable', 'in:day,week,month,quarter,year,all']
        ];
    }
}
