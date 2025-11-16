<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Debug\ShouldntReport;

class PassException extends Exception implements ShouldntReport
{
    //

    function report(): bool
    {
        // return false;
        return true;
    }
}
