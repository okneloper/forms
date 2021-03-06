<?php
/**
 * @author Aleksey Lavrinenko
 * @version 19.01.2016.
 */
namespace Okneloper\Forms\Exceptions;

class NotArrayException extends \Exception
{
    public function __construct($value, $what)
    {
        $message = "Value for $what should be an array. ";
        if (is_object($value)) {
            $type = get_class($value);
            $message .= "Instance of $type was provided";
        } else {
            $type = gettype($value);
            $message .= "$type was provided.";
        }

        parent::__construct($message);
    }
}
