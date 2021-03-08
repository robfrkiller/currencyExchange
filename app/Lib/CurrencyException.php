<?php

namespace App\Lib;

use Exception;

class CurrencyException extends Exception
{
    /**
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
