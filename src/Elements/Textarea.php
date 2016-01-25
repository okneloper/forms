<?php


namespace Okneloper\Forms\Elements;

use Okneloper\Forms\Element;

class Textarea extends Element
{
    static protected $defaultAttributes = ['class' => 'text'];

    protected $type = 'area';

    public function render()
    {
        $attrs = $this->getAttributes();
        if (isset($attrs['value'])) {
            unset($attrs['value']);
        }
        return '<textarea ' . $this->buildAttrs($attrs) . '>'
            . clean_page($this->attr('value'))
            . '</textarea>';
    }
}