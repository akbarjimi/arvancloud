<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoucherSubmitFormRequest;
use App\Http\Resources\VoucherApplyingFailed;
use App\Http\Resources\VoucherApplyingSuccessful;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\TransactionsEnum;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class VoucherController extends Controller
{
    public function submit(VoucherSubmitFormRequest $request)
    {
        try {
            DB::beginTransaction();

            /** @var Voucher $voucher */
            $voucher = Voucher::where('code', $request->post('code'))->first();

            /** @var User $user */
            $user = User::where('mobile', $request->post('mobile'))->first();

            /** @var Account $account */
            $account = $user->account()->first();

            /** @var Transaction $transaction */
            $transaction = $account->transactions()->create([
                'amount' => $voucher->value,
                'type' => TransactionsEnum::toCode(TransactionsEnum::VOUCHER),
                'description' => "from voucher: " . $voucher->code,
            ]);
            $transaction->updateBalance();
            $voucher->update([
                'used' => $voucher->used + 1,
                'expire_at' => $voucher->expire_at
            ]);

            DB::commit();

            return new VoucherApplyingSuccessful([
                'code' => $voucher->code,
                'amount' => $voucher->value,
                'timestamp' => now()->toDateTimeString(),
            ]);
        } catch (Throwable $throwable) {
            DB::rollBack();
            report($throwable);
            return new VoucherApplyingFailed([
                'code' => $voucher->code,
            ]);
        }
    }

    public function report(Request $request)
    {

    }
}
