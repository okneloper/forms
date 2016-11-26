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
}
