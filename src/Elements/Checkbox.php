<?php

namespace Okneloper\Forms\Elements;

use Okneloper\Forms\Element;

class Checkbox extends Element
{
    static protected $defaultAttributes = ['value' => 1];

    protected $type = 'checkbox';

    public function checkedIf($condition)
    {
        if ($condition) {
            $this->attr('checked', true);
        }

        return $this;
    }

    public function val($value = null)
    {
        if ($value !== null) {

            $eventParams = [
                'oldValue' => $this->attr('checked'),
            ];

            // @todo should it be === ?
            $this->attr('checked', $this->attr('value') == $value);

            $this->trigger('valueChanged', $eventParams);

            return $this;
        }

        // when a checkbox is not checked, the value of it is null (ie not set)
        return $this->attr('checked') ? $this->attr('value') : null;
    }

    public function attr($name, $value = null)
    {
        if ($name == 'checked' && $value !== null) {
            $this->attributes[$name] = $value;
            $this->trigger('valueChanged', [
                'oldValue' => isset($this->attributes[$name]) ? $this->attributes[$name] : null,
            ]);

            return $this;
        } else {
            return parent::attr($name, $value);
        }
    }

    /*
    public function render()
    {
        return '<input ' . $this->buildAttrs(['type' => 'hidden', 'name' => $this->name, 'value' => 0]) . '>' . parent::render();
    }
    */

}