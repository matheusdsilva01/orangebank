<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'fromAccountId' => ['required', 'string', 'exists:accounts,id'],
            'toAccountId' => ['required', 'string', 'exists:accounts,id'],
            'amount' => ['required', 'numeric', 'min:0'],
        ];
    }
}
