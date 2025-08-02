<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountWithdrawRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'number' => ['required', 'string', 'exists:accounts,number'],
            'amount' => ['required', 'numeric', 'min:1'],
        ];
    }
}
