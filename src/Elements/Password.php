<?php

namespace Okneloper\Forms\Elements;

use Okneloper\Forms\Filters\VoidFilter;

class Password extends Text
{
    protected $type = 'password';

    public function getDefaultFilter()
    {
        return new VoidFilter();
    }

    /**
     * Prevent `value` attribute of password input from rendering
     * @param $attrs
     * @return string
     */
    protected function buildAttrs($attrs)
    {
        if (isset($attrs['value'])) {
            unset($attrs['value']);
        }
        return parent::buildAttrs($attrs);
    }
}
