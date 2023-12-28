<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'code',
        'used',
        'use',
        'value',
        'expire_at',
    ];

    protected $casts = [
        'expire_at' => 'datetime'
    ];

    public function transactions(): BelongsToMany
    {
        return $this->belongsToMany(
            Transaction::class,
            'transaction_voucher',
            'voucher_id',
            'transaction_id',
            'id',
            'id',
            __FUNCTION__,
        );
    }
}
