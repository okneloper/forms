<?php


namespace Oknedev\Forms\Elements;

use Oknedev\Forms\Element;

class Amount extends Element
{
    static protected $defaultAttributes = ['class' => 'text', 'step' => 'any', 'min' => 0];

    protected $type = 'number';

}