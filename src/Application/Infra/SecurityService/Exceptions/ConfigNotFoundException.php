<?php

namespace Printdeal\Voyager\Application\Infra\SecurityService\Exceptions;

use Throwable;

class ConfigNotFoundException extends \Exception
{
    const DEFAULT_MESSAGE = 'Config not found';

    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}