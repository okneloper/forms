<?php

use Okneloper\Forms\Elements\ArrayIndexed;

class ArrayIndexedTest extends PHPUnit_Framework_TestCase
{
    public function testItCanBeCreated()
    {
        $el = new ArrayIndexed(new \Okneloper\Forms\Elements\Text('text'));
        $this->assertInstanceOf(ArrayIndexed::class, $el);
    }
}
