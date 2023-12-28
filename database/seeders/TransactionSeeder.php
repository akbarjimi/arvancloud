<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\TransactionsEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $dispatcher = Transaction::getEventDispatcher();
        Transaction::unsetEventDispatcher();
        /** @var User $user */
        foreach (User::all() as $user) {
            $user->transactions()->create(Transaction::factory()->makeOne([
                'type' => TransactionsEnum::toCode(TransactionsEnum::DEPOSIT),
                'description' => "پول اولیه",
            ])->toArray())->updateBalance();
        }

        Transaction::setEventDispatcher($dispatcher);
    }
}
