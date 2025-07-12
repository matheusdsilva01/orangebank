<?php

namespace App\Exceptions;

use Exception;

class AccountException extends Exception
{

    public static function cannotTransferToSelfAccount(): self
    {
        return new self('Cannot transfer to the same account.');
    }

    public static function insufficientBalance(): self
    {
        return new self('Insufficient balance for the transfer.');
    }

    public static function onlyTransferBetweenCurrentAccountsFromDifferentUsers(): self
    {
        return new self('External transfers are only allowed between current accounts.');
    }

    public static function cannotTransferBetweenSameTypeAccounts(): self
    {
        return new self('Cannot transfer between accounts of the same type.');
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        //
    }
}
