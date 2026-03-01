<?php

namespace App\Exceptions;

use Exception;
use Inertia\Response;

class InertiaValidationException extends Exception
{
    public function __construct(
        public readonly Response $response,
    ) {
        parent::__construct('Validation failed.');
    }
}
