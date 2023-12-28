<?php

namespace Database\Factories;

use App\Models\TransactionsEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class TransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'account_id' => null,
            'amount' => random_int(50000, 50000 * 40/** 2,000,000 */),
            'type' => Arr::random([
                TransactionsEnum::toCode(TransactionsEnum::DEPOSIT),
                TransactionsEnum::toCode(TransactionsEnum::WITHDRAWAL),
            ]),
            'balance' => 0,
            'description' => $this->faker->text(),
        ];
    }

    public function voucher()
    {
        return $this->state(function (array $attributes) {
            return [
                'account_id' => null,
                'amount' => 50000 * 20/** 1,000,000 */,
                'type' => TransactionsEnum::toCode(TransactionsEnum::VOUCHER),
                'balance' => 0,
                'description' => trans("strings.transactions.fee.message"),
            ];
        });
    }
}
