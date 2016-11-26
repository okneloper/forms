<?php

namespace Okneloper\Forms\Filters;

/**
 * Class NativeFilter
 *
 * Filters variable using PHP\s native filter_var function
 *
 * @package okneloper/forms
 */
class NativeFilter implements FilterInterface
{
    protected $filter;
    protected $options;

    public function __construct($filter, $options = null)
    {
        $this->filter  = $filter;
        $this->options = $options;
    }

    public function filter($name, $value)
    {
        // do not filter objects
        if (is_object($value)) {
            return $value;
        }

        $value = filter_var($value, $this->filter, $this->options);
        
        return $value;
    }
}
