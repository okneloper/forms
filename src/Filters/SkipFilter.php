<?php

namespace Okneloper\Forms\Filters;

/**
 * Skips the value whatsoever
 */
class SkipFilter implements FilterInterface
{
    public function filter($name, $value)
    {
        throw new \BadMethodCallException("This filter should not be run!");
    }
}
