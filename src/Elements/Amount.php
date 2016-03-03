<?php


namespace Okneloper\Forms\Elements;

use Okneloper\Forms\Element;

/**
 * Class Amount. Input type number with some amount-specific defaults.
 *
 * @package Okneloper\Forms\Elements
 * @author Aleksey Lavrinenko
 */
class Amount extends Number
{
    static protected $defaultAttributes = [
        'class' => 'text',
        'step' => '0.01',
        'min' => 0
    ];
}
