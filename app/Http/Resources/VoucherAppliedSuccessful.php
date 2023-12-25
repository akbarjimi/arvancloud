<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherAppliedSuccessful extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'success' => true,
            'message' => trans('strings.transfer_succeed.message'),
            'transaction' => [
                'from' => $this->resource['from'],
                'amount' => $this->resource['amount'],
                'timestamp' => $this->resource['timestamp'],
            ],
        ];
    }
}
