<?php

namespace Okneloper\Forms\Elements;

use Okneloper\Forms\Element;

class Date extends Text
{
    protected $type = 'date';

    protected $format = 'Y-m-d';

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    protected function buildAttr($name, $value)
    {
        if ($value instanceof \DateTime) {
            $value = $value->format($this->format);
        }
        return parent::buildAttr($name, $value);
    }


    public function render__()
    {
        $attrs = $this->getAttributes() + ['type' => $this->type];

        return '<input ' . $this->buildAttrs($attrs) . '>';
    }
}
