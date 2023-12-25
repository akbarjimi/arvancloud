<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $dispatcher = Account::getEventDispatcher();
        Account::unsetEventDispatcher();
        foreach (User::all() as $user) {
            Account::factory()
                ->for($user)
                ->create();
        }
        Account::setEventDispatcher($dispatcher);
    }
}
