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

            $this->checkedIf($this->attr('value') == $value);

            $this->trigger('valueChanged', $eventParams);

            return $this;
        }

        return $this->attr('checked') ? $this->attr('value') : false;
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