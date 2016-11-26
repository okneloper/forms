<?php

namespace Okneloper\Forms\Filters;

/**
 * Composite implementation fot Filters
 */
class CompositeFilter implements FilterInterface
{
    /**
     * @var FilterInterface[]
     */
    protected $filters = [];

    public function addFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;
    }

    public function filter($name, $value)
    {
        foreach ($this->filters as $filter) {
            $value = $filter->filter($name, $value);
        }
        return $value;
    }
}
