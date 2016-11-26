<?php

namespace Okneloper\Forms\Elements;

use Okneloper\Forms\Element;
use Okneloper\Forms\Filters\SkipFilter;

class Button extends Element
{
    protected $type = 'button';

    public function render()
    {
        $attributes = ['type' => $this->type] + $this->getAttributes();

        if ($this->value) {
            $attributes += ['value' => $this->value];
        }
        return '<button ' . $this->buildAttrs($attributes) . '>' . $this->escape($this->label) . '</button>';
    }

    public function getDefaultFilter()
    {
        return new SkipFilter();
    }
}
