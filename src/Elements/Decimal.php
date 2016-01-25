<?php


namespace Oknedev\Forms\Elements;

use Oknedev\Forms\Element;

class Decimal extends Element
{
    static protected $defaultAttributes = ['class' => 'text', 'step' => 'any'];

    protected $type = 'number';

}