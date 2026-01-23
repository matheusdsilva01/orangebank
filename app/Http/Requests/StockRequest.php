<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
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
            'name' => ['required'],
            'symbol' => ['required'],
            'sector' => ['required'],
            'current_price' => ['required', 'numeric'],
            'daily_variation' => ['required', 'numeric'],
        ];
    }
}
