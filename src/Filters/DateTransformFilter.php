<?php
/*
 * This file is part of the Forms package.
 *
 * (c) Aleksey Lavrinenko <okneloper@gmail.com>
 */
namespace Okneloper\Forms\Filters;

class DateTransformFilter implements FilterInterface
{
    protected $formatFrom;
    protected $formatTo;

    public function __construct($formatFrom, $formatTo = 'Y-m-d')
    {
        $this->formatFrom = $formatFrom;
        $this->formatTo = $formatTo;
    }

    public function filter($name, $value)
    {
        $date = \DateTime::createFromFormat($this->formatFrom, $value);

        // skip the filter is the date cannot be parsed
        if (!($date instanceof \DateTime)) {
            return $value;
        }
        return $date->format($this->formatTo);
    }
}
