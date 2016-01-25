<?php

namespace Okneloper\Forms\Elements;

use Okneloper\Forms\Exceptions\InvalidMultipleValueException;

class Select extends Choice
{
    protected $type = 'select';

    public function render()
    {
        $return = '<select ' . $this->buildAttrs($this->getAttributes()) . '>' . PHP_EOL;
        foreach ($this->options as $val => $text) {
            $selected = $this->selected($val) ? ' selected' : '';
            $return .= "<option value=\"$val\" $selected>{$this->escape($text)}</option>" . PHP_EOL;
        }
        $return .= '</select>';

        return $return;
    }

    protected function selected($value)
    {
        if ($this->multiple()) {
            $thisValue = $this->val();
            return in_array($value, $thisValue);
        }
        return $value == $this->val();
    }

    public function multiple($newValue = null)
    {
        if ($newValue === null) {
            return $this->attr('multiple');
        }

        $this->attr('multiple', $newValue, true);

        if ($newValue && $this->val() === null) {
            $this->val([]);
        }

        $this->assertMultipleValueIsArray($this->val());

        return $this;
    }

    /**
     * @param $name
     * @param null $value
     * @param bool $delegate Delegate setting of the attribute to the parent function to prevent endless recursion.
     * @return $this|Select
     */
    public function attr($name, $value = null, $delegate = false)
    {
        // only call multiple() if not delegated, ie the method was not called by multiple() itself
        if ($name === 'multiple' && $value !== null && !$delegate) {
            return $this->multiple($value);
        }
        return parent::attr($name, $value);
    }

    public function val($value = null)
    {
        if ($value !== null) {
            $this->assertMultipleValueIsArray($value);
        }

        $return = parent::val($value);

        // set data-value for dropdowns with dynamic options
        // this allows to defer selecting an option until the moment the options are there
        $this->data('value', $value);

        return $return;
    }

    public function assertMultipleValueIsArray($value)
    {
        if ($this->multiple() && !is_array($value)) {
            throw new InvalidMultipleValueException($value);
        }
    }

    protected function buildAttrs($attrs)
    {
        if (isset($attrs['value'])) {
            unset($attrs['value']);
        }
        return parent::buildAttrs($attrs);
    }
}
