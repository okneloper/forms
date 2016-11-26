<?php

namespace Okneloper\Forms\Filters;

/**
 * Strips tags from a string
 */
class StripTagsFilter implements FilterInterface
{
    /**
     * Allowable tags in strip_tags() format
     * @var string
     */
    protected $allowableTags;

    /**
     * StripTagsFilter constructor.
     * @param string $allowableTags
     */
    public function __construct($allowableTags = '')
    {
        $this->allowableTags = $allowableTags;
    }

    /**
     * Perform the filtering
     * @param $name
     * @param $value
     * @return string
     */
    public function filter($name, $value)
    {
        return strip_tags($value, $this->allowableTags);
    }
}
