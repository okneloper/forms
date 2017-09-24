<?php

namespace Okneloper\Forms\Observers;

use Okneloper\Forms\ElementInterface;

interface ValueObserver
{
    public function valueChanged(ElementInterface $element, $oldValue);
}
