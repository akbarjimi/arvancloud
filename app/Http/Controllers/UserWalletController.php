<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserWalletReportRequest;
use App\Http\Resources\UserWalletResource;
use App\Models\User;

class UserWalletController extends Controller
{
    public function report(UserWalletReportRequest $request)
    {
        /** @var User $user */
        $user = User::where('mobile', $request->post('mobile'))->first();

        return new UserWalletResource($user);
    }
}
