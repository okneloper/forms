<?php

namespace Okneloper\Forms\Elements;

class Date extends Text
{
    protected $type = 'date';

    protected $format = 'Y-m-d';

    protected $inputFormat = 'Y-m-d';

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
        if ($inputFormat !== null) {
            $this->inputFormat = $inputFormat;
        }
    }

    /**
     * @return string
     */
    public function getInputFormat()
    {
        return $this->inputFormat;
    }

    /**
     * @param string $inputFormat
     */
    public function setInputFormat($inputFormat)
    {
        $this->inputFormat = $inputFormat;
    }



    protected function buildAttr($name, $value)
    {
        if ($value instanceof \DateTime) {
            $value = $value->format($this->format);
        }
        return parent::buildAttr($name, $value);
    }

    public function val($value = null)
    {
        if (is_string($value) && $value) {
            // if value is a string and is parseable into a DateTime, then set this Datetime as Value
            $dt = \DateTime::createFromFormat($this->inputFormat, $value);
            if ($dt) {
                $value = $dt;
            }
        }

        return parent::val($value);
    }


}
