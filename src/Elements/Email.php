<?php

namespace Okneloper\Forms\Elements;

use Okneloper\Forms\Element;

class Email extends Element
{
    static protected $defaultAttributes = ['class' => 'text'];

    protected $type = 'email';

}