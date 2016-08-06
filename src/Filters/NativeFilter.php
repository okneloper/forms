<?php

namespace Okneloper\Forms\Filters;

/**
 * Class FilterPhpNative
 *
 * Filter variable using PHP\s native filter_var function
 *
 * Custom option is 'trim' (defaults to true) - trim the value after filtering
 *
 * @package okneloper/forms
 */
class NativeFilter implements FilterInterface
{
    protected $filter;
    protected $options;

    public function __construct($filter, $options = null)
    {
        if (!isset($options['trim'])) {
            $options['trim'] = true;
        }

        $this->filter  = $filter;
        $this->options = $options;
    }

    public function filter($name, $value)
    {
        // do not filter objects
        if (is_object($value)) { return $value; }

        $value = filter_var($value, $this->filter, $this->options);
        if ($this->options['trim'] && is_string($value)) {
            $value = trim($value);
        }
        return $value;
    }
}