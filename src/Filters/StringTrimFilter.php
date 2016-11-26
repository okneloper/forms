<?php

namespace Okneloper\Forms\Filters;

/**
 * Applies trim() function
 */
class StringTrimFilter implements FilterInterface
{
    public function filter($name, $value)
    {
        return trim($value);
    }
}
