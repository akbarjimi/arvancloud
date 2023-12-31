<?php

namespace App\Http\Controllers;

use App\Enum\TransactionsEnum;
use App\Exceptions\VoucherMaxUse;
use App\Http\Requests\VoucherReportFormRequest;
use App\Http\Requests\VoucherSubmitFormRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\VoucherApplyingFailed;
use App\Http\Resources\VoucherApplyingSuccessful;
use App\Models\Transaction;
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

            $uses = $voucher->transactions()->whereRelation('user', 'id', '=', $user->id)->get();
            if ($uses->count() >= $voucher->used_up_to) {
                throw new VoucherMaxUse($voucher->code);
            }

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
            ]);

            DB::commit();

            return new VoucherApplyingSuccessful([
                'code' => $voucher->code,
                'amount' => $voucher->value,
                'timestamp' => now()->toDateTimeString(),
            ]);

        } catch (VoucherMaxUse $throwable) {
            throw $throwable;
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
        return new UserCollection(collect($users)->unique('mobile'));
    }
}
