<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockHistoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'daily_variation' => ['required', 'numeric'],
            'daily_price' => ['required', 'numeric'],
            'stock_id' => ['required', 'exists:stocks'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
