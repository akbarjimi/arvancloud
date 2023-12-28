<?php

namespace App\Models;

use App\Enum\TransactionsEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'balance',
        'description',
    ];


//    protected $dispatchesEvents = [
//        'created' => TransactionSavedEvent::class,
//    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getSignedAmount()
    {
        if (in_array($this->type, [TransactionsEnum::toCode(TransactionsEnum::WITHDRAWAL)])) {
            return $this->amount * -1;
        }

        return $this->amount;
    }

    public function updateBalance(): void
    {
        $this->update([
            'balance' => $this->user->latestTransaction->balance + $this->getSignedAmount(),
        ]);
    }
}
