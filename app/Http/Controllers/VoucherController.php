<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoucherSubmitFormRequest;
use App\Http\Resources\TransferSuccessful;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
            // اینجا یا منطق میخواد یا اینکه بهتره غیرفعال شه
            /** @var Account $account */
            $account = $user->accounts()->inRandomOrder()->first();
            /** @var Transaction $transaction */
            $transaction = $account->transactions()->create([
                'amount' => $voucher->value,
                'type' => Transaction::VOUCHER,
                'description' => "from voucher: " . $voucher->code,
            ]);
            $transaction->updateBalance();
            $voucher->update([
                'used' => $voucher->used + 1,
                'expire_at' => $voucher->expire_at
            ]);

            DB::commit();

            return new TransferSuccessful([
                'from' => $voucher->code,
                'amount' => $voucher->value,
                'timestamp' => now()->toDateTimeString(),
            ]);
        } catch (Throwable $throwable) {
            DB::rollBack();
            report($throwable);
            abort(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function report(Request $request)
    {

    }
}
