<?php

namespace Okneloper\Forms\Observers;

use Okneloper\Forms\Element;

interface ValueObserver
{
    public function valueChanged(Element $element, $oldValue);
}