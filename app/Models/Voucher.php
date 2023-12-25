<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
