<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VoucherFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => Str::random(6),
            'use' => 1000,
            'used' => 0,
            'used_up_to' => 1,
            'value' => 50000 * 20/** 1,000,000 */,
            'expire_at' => now()->addDay(),
        ];
    }
}
