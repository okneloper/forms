<?php

namespace Okneloper\Forms\Filters;

/**
 * Class ArrayFilter.
 * Applies the filter to all elements of an array recursively
 *
 * @package Okneloper\Forms\Filters
 */
class ArrayFilter implements FilterInterface
{
    /**
     * @var FilterInterface
     */
    protected $filter;

    /**
     * @param FilterInterface $filter
     */
    public function __construct(FilterInterface $filter)
    {
        $this->filter = $filter;
    }

    public function filter($name, $value)
    {
        if (is_array($value)) {
            foreach ($value as &$arrayValue) {
                $arrayValue = $this->filter->filter($name, $arrayValue);
            }
            return $value;
        } else {
            return $this->filter->filter($name, $value);
        }
    }
}
