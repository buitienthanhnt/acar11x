<?php

namespace App\Exceptions;

use Exception;

class ContentException extends Exception
{

    /**
     * define render method to show custom error page
     */
    function render()
    {
        return   view('components.errors', ['errors' => $this->getMessage()]);
    }
}
