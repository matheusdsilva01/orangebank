<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required'],
            'symbol' => ['required'],
            'sector' => ['required'],
            'current_price' => ['required', 'numeric'],
            'daily_variation' => ['required', 'numeric'],
        ];
    }
}
