<?php

namespace Okneloper\Forms\Elements;

use Okneloper\Forms\Element;

/**
 *
 * Temporary element for use with radio buttons and rendering manually
 *
 * Class Choice
 * @package Okneloper\Forms\Elements
 */
class Choice extends Element
{
    protected $type = 'choice';

    protected $options;

    public function __construct($name, $label = null, $attributes = [])
    {
        if (isset($attributes['options'])) {
            $this->options = $attributes['options'];
            unset($attributes['options']);
        } else {
            $this->options = [];
        }

        parent::__construct($name, $label, $attributes);
    }

    public function render()
    {
        throw new \BadMethodCallException("Choice element cannot be rendered");
    }

    /**
     * Set or get dropdown options.
     *
     * @param null $options
     * @param \Closure|null $extractOption
     * @return $this|array
     */
    public function options($options = null, \Closure $extractOption = null)
    {
        if ($options === null) {
            return $this->options;
        }

        if ($extractOption !== null) {
            $cleanOptions = [];
            foreach ($options as $option) {
                list($value, $text) = $extractOption($option);
                $cleanOptions[$value] = $text;
            }
            $options = $cleanOptions;
        }

        $this->options = array_merge($this->options, $options);

        return $this;
    }
}
