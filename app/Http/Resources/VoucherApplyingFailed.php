<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherApplyingFailed extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'success' => false,
            'error' => [
                'message' => trans('strings.voucher.applying_failed.message'),
                'code' => $this->resource['code'],
                'details' => trans('strings.voucher.applying_failed.details'),
            ],
        ];
    }
}
