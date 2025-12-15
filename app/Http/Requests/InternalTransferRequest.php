<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InternalTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric:', 'min:1'],
            'mode' => ['required', 'in:current-investment,investment-current'],
        ];
    }
}
