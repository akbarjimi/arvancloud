<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserWalletResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'balance' => $this->whenNotNull($this->transactions->sortByDesc('id')->first()?->balance, 0),
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
        ];
    }
}
