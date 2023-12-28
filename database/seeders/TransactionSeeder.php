<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\TransactionsEnum;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $dispatcher = Transaction::getEventDispatcher();
        Transaction::unsetEventDispatcher();
        /** @var Account $account */
        foreach (Account::all() as $account) {
            $account->transactions()->create(Transaction::factory()->makeOne([
                'type' => TransactionsEnum::toCode(TransactionsEnum::DEPOSIT),
                'description' => "پول اولیه",
            ])->toArray())->updateBalance();
        }

        Transaction::setEventDispatcher($dispatcher);
    }
}
