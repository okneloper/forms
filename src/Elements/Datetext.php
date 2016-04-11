<?php

namespace Okneloper\Forms\Elements;

/**
 * Class Date. Date input rendered as Text input (type="text")
 * @package Okneloper\Forms\Elements
 * @author Aleksey Lavrinenko
 */
class Datetext extends Date
{
    protected $type = 'text';

    protected $inputFormat = 'Y-m-d';

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
    public function setFormat($format, $inputFormat = null)
    {
        $this->format = $format;
        if (isset($inputFormat)) {
            $this->inputFormat = $inputFormat;
        }
    }

    protected function buildAttr($name, $value)
    {
        if ($name === 'value') {
            if (is_string($value) && $value) {
                $value = \DateTime::createFromFormat($this->inputFormat, $value);
            }

            if ($value instanceof \DateTime) {
                $value = $value->format($this->format);
            }
        }
        return parent::buildAttr($name, $value);
    }


    public function render__()
    {
        $attrs = $this->getAttributes() + ['type' => $this->type];

        return '<input ' . $this->buildAttrs($attrs) . '>';
    }
}
