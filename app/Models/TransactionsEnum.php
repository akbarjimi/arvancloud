<?php

namespace App\Models;

enum TransactionsEnum
{
    case VOUCHER;
    case DEPOSIT;
    case WITHDRAWAL;

    public static function toCode(TransactionsEnum $enum): int
    {
        return match ($enum) {
            TransactionsEnum::VOUCHER => 10,
            TransactionsEnum::DEPOSIT => 20,
            TransactionsEnum::WITHDRAWAL => 30,
        };
    }

    public static function fromCode(int $code): TransactionsEnum
    {
        return match ($code) {
            10 => TransactionsEnum::VOUCHER,
            20 => TransactionsEnum::DEPOSIT,
            30 => TransactionsEnum::WITHDRAWAL,
        };
    }

    public static function toString(TransactionsEnum $enum): string
    {
        return match ($enum) {
            TransactionsEnum::VOUCHER => 'کد تخفیف',
            TransactionsEnum::DEPOSIT => 'سپرده',
            TransactionsEnum::WITHDRAWAL => 'برداشت',
        };
    }
    public static function codeToString(int $code): string
    {
        return TransactionsEnum::toString(TransactionsEnum::fromCode($code));
    }
}
