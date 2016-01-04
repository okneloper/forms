<?php


use Oknedev\Forms\Element;

class ElementTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider namesProvider
     *
     * @param $name
     * @param $expected
     */
    public function testCleansName($name, $expected)
    {
        $el = new Element($name, []);
        $this->assertEquals($el->name, $expected);
    }

    public function namesProvider()
    {
        return [
            ['quantity',      'quantity'],
            ['quantity[]',    'quantity'],
            ['quantity[1]',   'quantity'],
            ['quantity[1][]', 'quantity'],
        ];
    }
}
