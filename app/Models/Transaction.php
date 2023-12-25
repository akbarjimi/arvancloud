<?php

namespace App\Models;

use App\Events\TransactionSavedEvent;
use App\Exceptions\INSUFFICIENT_FUNDS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    const VOUCHER = 10;
    const DEPOSIT = 20;
    const WITHDRAWAL = 30;

    protected $fillable = [
        'account_id',
        'amount',
        'type',
        'balance',
        'description',
    ];


//    protected $dispatchesEvents = [
//        'created' => TransactionSavedEvent::class,
//    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Account::class);
    }

    public function getSignedAmount()
    {
        if (in_array($this->type, [static::WITHDRAWAL])) {
            return $this->amount * -1;
        }

        return $this->amount;
    }

    public function updateBalance(): void
    {
        $this->update([
            'balance' => $this->account->transactions()
                    ->whereKeyNot($this->id)->latest($this->getKeyName())
                    ->first()?->balance + $this->getSignedAmount(),
        ]);
    }
}
