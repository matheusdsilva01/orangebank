<?php

namespace App\Enums;

enum AccountType: string
{
    case Current = 'current';
    case Investment = 'investment';
}
