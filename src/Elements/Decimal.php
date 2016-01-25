<?php


namespace Okneloper\Forms\Elements;

use Okneloper\Forms\Element;

class Decimal extends Element
{
    static protected $defaultAttributes = ['class' => 'text', 'step' => 'any'];

    protected $type = 'number';

}