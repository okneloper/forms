<?php

namespace Oknedev\Forms\Elements;

use Oknedev\Forms\Element;

class Email extends Element
{
    static protected $defaultAttributes = ['class' => 'text'];

    protected $type = 'email';

}