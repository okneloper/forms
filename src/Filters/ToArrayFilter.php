<?php

namespace Okneloper\Forms\Filters;

class ToArrayFilter implements FilterInterface
{
    public function filter($name, $value)
    {
        return empty($value) ? [] : (array)$value;
    }
}
