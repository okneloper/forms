<?php

namespace Okneloper\Forms;

interface ElementInterface
{
    /**
     * jQuery-like value getter/setter
     *
     * @param null $value
     * @return mixed|$this current value or $this
     */
    public function val($value = null);

    /**
     * Set the value even is the element is readonly/disabled (for setting default values)
     * @param $value
     * @return $this
     */
    public function forceValue($value);

    /**
     * jQuery-like attribute getter/setter
     *
     * @param $name
     * @param null $value
     * @return $this
     */
    public function attr($name, $value = null);
}
