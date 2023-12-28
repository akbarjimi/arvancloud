<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoucherReportFormRequest;
use App\Http\Requests\VoucherSubmitFormRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\VoucherApplyingFailed;
use App\Http\Resources\VoucherApplyingSuccessful;
use App\Models\Transaction;
use App\Models\TransactionsEnum;
use App\Models\User;
use App\Models\Voucher;
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

            /** @var Transaction $transaction */
            $transaction = $user->transactions()->create([
                'amount' => $voucher->value,
                'type' => TransactionsEnum::toCode(TransactionsEnum::VOUCHER),
                'description' => "from voucher: " . $voucher->code,
            ]);
            $transaction->updateBalance();
            $voucher->transactions()->save($transaction);
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

    public function report(VoucherReportFormRequest $request)
    {
        /** @var Voucher $voucher */
        $voucher = Voucher::with('transactions.user')->where('code', $request->post('code'))->first();
        $users = [];
        foreach ($voucher->transactions as $transaction) {
            $users[] = $transaction->user;
        }
        return new UserCollection($users);
    }
}
