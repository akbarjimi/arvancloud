<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherMaxUseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'success' => false,
            'error' => [
                'message' => trans('strings.voucher.max_used.message'),
                'code' => $this->resource['code'],
                'details' => trans('strings.voucher.max_used.details'),
            ],
        ];
    }
}
