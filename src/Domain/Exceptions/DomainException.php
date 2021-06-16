<?php


namespace Domain\Exceptions;


use Throwable;

class DomainException extends \Exception
{
    public function __construct($message = "", $code = 422, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
