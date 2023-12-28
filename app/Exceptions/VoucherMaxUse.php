<?php

namespace App\Exceptions;

use App\Http\Resources\VoucherMaxUseResource;
use Exception;
use Illuminate\Http\Request;

class VoucherMaxUse extends Exception
{
    public $code;

    public function __construct(string $code)
    {
        $this->code = $code;
        parent::__construct();
    }

    public function render(Request $request)
    {
        return new VoucherMaxUseResource([
            'code' => $this->code
        ]);
    }
}
