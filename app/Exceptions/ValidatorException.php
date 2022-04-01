<?php

namespace App\Exceptions;

use Illuminate\Contracts\Support\MessageBag;
use Exception;

/**
 * Get the messages for the instance.
 *
 * @param \Illuminate\Contracts\Support\MessageBag
 */
class ValidatorException extends Exception
{
    private $errors;

    public function __construct(MessageBag $errors) {
        $this->errors = $errors;
    }

    public function getErrors() {
        return $this->errors;
    }
}
