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
     * jQuery-like attribute getter/setter
     *
     * @param $name
     * @param null $value
     * @return $this
     */
    public function attr($name, $value = null);
}
