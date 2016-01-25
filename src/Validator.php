<?php


namespace Oknedev\Forms;


class Validator extends \Illuminate\Validation\Validator
{
    public function validatePhone($field, $value)
    {
        return preg_match('/^(\+|\(\d+\) ?)?\d+([\- ]\d+)*$/', $value);
    }

    public function validatePostcodeUk($field, $value)
    {
        $regExp = '/^([A-Za-z]{1,2}[0-9]{1,2}[a-zA-z]?)([\s]?)([0-9]{1}[A-Za-z]{2})$/';
        return preg_match($regExp, $value);
    }
}