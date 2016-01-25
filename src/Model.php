<?php

namespace Okneloper\Forms;

class Model extends \ArrayObject
{
    public function __get($prop)
    {
        return isset($this[$prop]) ? $this[$prop] : null;
    }

    public function __set($prop, $value)
    {
        $this[$prop] = $value;
    }

    public function __isset($prop)
    {
        return isset($this[$prop]);
    }

    public function __unset($prop)
    {
        unset($this[$prop]);
    }
}
