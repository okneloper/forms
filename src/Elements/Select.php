<?php

namespace Okneloper\Forms\Elements;

class Select extends Choice
{
    protected $type = 'select';

    public function render()
    {
        $selectedOption = $this->val();

        $return = '<select ' . $this->buildAttrs($this->getAttributes()) . '>' . PHP_EOL;
        foreach ($this->options as $val => $text) {
            $selected = $val == $selectedOption ? ' selected' : '';
            $return .= "<option value=\"$val\" $selected>{$this->escape($text)}</option>" . PHP_EOL;
        }
        $return .= '</select>';

        return $return;
    }

    public function attr77($name, $value = null)
    {
        if ($name == 'value') {

        } else {
            parent::attr($name, $value);
        }
    }
}
