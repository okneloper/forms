<?php

namespace Okneloper\Forms\Observers;

use Okneloper\Forms\Element;

interface AttributeObserver
{
    public function attributeChanged(Element $element, $attrName, $oldValue);
}