<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AccountException extends Exception
{
    public static function cannotTransferToSelfAccount(): self
    {
        return new self('Cannot transfer to the same account.', Response::HTTP_FORBIDDEN);
    }

    public static function insufficientBalance(): self
    {
        return new self('Insufficient balance for the transfer.', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public static function onlyTransferBetweenCurrentAccountsFromDifferentUsers(): self
    {
        return new self('External transfers are only allowed between current accounts.', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public static function cannotTransferBetweenSameTypeAccounts(): self
    {
        return new self('Cannot transfer between accounts of the same type.', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public static function cannotWithdrawFromInvestmentAccount(): self
    {
        return new self('Cannot withdraw from investment account.', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public static function cannotDepositToInvestmentAccount(): self
    {
        return new self('Cannot deposit to investment account.', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public static function cannotBuyStockWithoutAnInvestmentAccount(): self
    {
        return new self('Cannot buy without an investment account.', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public static function internalTransfersCanOnlyBeMadeBetweenAccountsOfTheSameUser(): self
    {
        return new self('Internal transfers can only be made between accounts of the same user.', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public static function accountNotFound(): self
    {
        return new self('Account not found.', Response::HTTP_NOT_FOUND);
    }

    public static function invalidDepositAmount(): self
    {
        return new self('Invalid deposit amount.', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        //
    }
}
