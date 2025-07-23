<?php

namespace Resumenter\Exception;

use Exception;
use Throwable;

final class NotFoundException extends HttpException
{
    private const CODE = 404;
    private const MESSAGE = "Page '%s' not found";
    public function __construct(string $page)
    {
        parent::__construct($page,self::MESSAGE, self::CODE);
    }
}