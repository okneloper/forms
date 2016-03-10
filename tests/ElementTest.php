<?php

class ElementTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test if name[] style names are processed correctly
     * @dataProvider namesProvider
     *
     * @param $name
     * @param $expected
     */
    public function testCleansName($name, $expected)
    {
        $el = new \Okneloper\Forms\Element($name, []);
        $this->assertEquals($el->name, $expected);
    }

    public function testDisables()
    {
        $el = new \Okneloper\Forms\Element('test');
        #$this->assertSame(false, $el->attr('disabled'));
        $this->assertSame(null, $el->attr('disabled'));

        $el->disable();
        $this->assertSame(true, $el->attr('disabled'));
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
