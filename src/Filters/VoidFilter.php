<?php
namespace Okneloper\Forms\Filters;

/**
 * Does not filter anything, simply returns the value.
 */
class VoidFilter implements FilterInterface
{
    public function filter($name, $value)
    {
        return $value;
    }
}
