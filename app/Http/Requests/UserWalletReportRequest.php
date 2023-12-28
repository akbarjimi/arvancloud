<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserWalletReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mobile' => [
                'required',
                'numeric',
                'digits:11',
                'exists:users,mobile'
            ],
        ];
    }
}
