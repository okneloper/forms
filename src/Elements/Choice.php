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

    public function options($options = null)
    {
        if ($options) {
            $this->options = $options;
            return $this;
        } else {
            return $this->options;
        }
    }
}
