<?php

namespace App\Http\Requests;

use App\Models\Voucher;
use Illuminate\Foundation\Http\FormRequest;

class VoucherSubmitFormRequest extends FormRequest
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
            'code' => [
                'bail',
                'required',
                'alpha_num',
                'exists:vouchers,code',
                function (string $name, $value, $fail, $validator) {
                    $voucher = Voucher::where('code', $value)->first();
                    if ($voucher->expire_at->isPast()) {
                        $fail("validation.voucher.expired");
                    }
                    if ($voucher->used >= $voucher->use) {
                        $fail("validation.voucher.used_up");
                    }
                }
            ],
        ];
    }
}
