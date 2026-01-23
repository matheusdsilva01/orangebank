<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StockHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|list<string>|string>
     */
    public function rules(): array
    {
        return [
            'daily_variation' => ['required', 'numeric'],
            'daily_price' => ['required', 'numeric'],
            'stock_id' => ['required', 'exists:stocks'],
        ];
    }
}
