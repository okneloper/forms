<?php

namespace Okneloper\Forms\Validation;

/**
 * @author Aleksey Lavrinenko
 * @version 28.12.2015.
 */
class ValidationException extends \Exception
{
    public static function noValidatorResolver()
    {
        return new static("Unable to resolve validator. ValidatorResolver not provided via setValidatorResolver().");
    }

    public static function invalidValidatorClass()
    {
        return new static("Unexpected class of validator. Expecting validator of type " . ValidatorInterface::class);
    }
}
