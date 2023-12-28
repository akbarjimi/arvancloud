<?php

namespace App\Http\Resources;

use App\Models\TransactionsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => TransactionsEnum::codeToString($this->type),
            'balance' => $this->balance,
            'description' => $this->description,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
