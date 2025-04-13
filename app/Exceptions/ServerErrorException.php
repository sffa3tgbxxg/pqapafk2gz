<?php

namespace App\Exceptions;

use Exception;

class ServerErrorException extends Exception
{
    public function __construct($message = "Ошибка сервера", $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
