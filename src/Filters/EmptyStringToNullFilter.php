<?php
/**
 * @author Aleksey Lavrinenko
 * @version 07.03.2016.
 */
namespace Okneloper\Forms\Filters;

class EmptyStringToNullFilter implements FilterInterface
{
    public function filter($name, $value)
    {
        if ($value === '') {
            return null;
        }
        return $value;
    }
}
