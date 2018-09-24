<?php

namespace Okneloper\Forms\Elements;

use Okneloper\Forms\Element;
use Okneloper\Forms\Filters\VoidFilter;

class Checkbox extends Element
{
    protected $type = 'checkbox';

    /**
     * Value when checkbox is checked
     * Set default value attribute as '1' for Checkbox
     */
    protected $value = '1';

    /**
     * Value when checkbox is unchecked
     * @var string
     */
    protected $valueFalse = null;

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param string $valueFalse
     * @return $this
     */
    public function setValueFalse($valueFalse)
    {
        $this->valueFalse = $valueFalse;
        return $this;
    }

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
            if ($this->disabled() || $this->readonly()) {
                return $this;
            }

            $this->checkedIf($this->attr('value') == $value);

            return $this;
        }

        // when a checkbox is not checked, the value of it is null (ie not set)
        return $this->attr('checked') ? $this->value : $this->valueFalse;
    }

    public function forceValue($value)
    {
        $this->checkedIf($this->attr('value') == $value);

        return $this;
    }

    public function attr($name, $value = null)
    {
        if ($name == 'checked') {
            if ($value === null) {
                return !empty($this->attributes['checked']);
            } else {
                $oldValue = $this->attr('checked');
                $this->attributes[$name] = $value;
                // both val() and attr('checked', X) trigger a 'valueChanged' event
                $this->triggerValueChanged($oldValue);
                return $this;
            }
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

    public function getDefaultFilter()
    {
        return new VoidFilter();
    }
}
