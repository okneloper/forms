<?php


namespace Okneloper\Forms\Elements;

use Okneloper\Forms\Element;

class Textarea extends Element
{
    static protected $defaultAttributes = ['class' => 'text'];

    public function render()
    {
        $attrs = $this->getAttributes();
        if (isset($attrs['value'])) {
            unset($attrs['value']);
        }
        return '<textarea ' . $this->buildAttrs($attrs) . '>'
            . $this->escape($this->attr('value'))
            . '</textarea>';
    }
}
