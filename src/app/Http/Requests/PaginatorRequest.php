<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaginatorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'page' => ['integer', 'min:1'],
            'per_page' => ['integer', 'min:1', 'max:1000'],
        ];
    }
}
