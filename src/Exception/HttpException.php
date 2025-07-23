<?php

namespace Resumenter\Exception;
use Exception;
use Throwable;

abstract class HttpException extends Exception
{
    public function __construct(string $page, string $message, int $code, ?Throwable $previous = null)
    {
        parent::__construct(print_r($message, $page), $code, $previous);
    }
}