<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Transaction;
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
                'type' => Transaction::DEPOSIT,
                'description' => "پول اولیه",
            ])->toArray())->updateBalance();
        }

        Transaction::setEventDispatcher($dispatcher);
    }
}
