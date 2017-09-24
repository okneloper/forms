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

    public function testSetsValueViaAttr()
    {
        $el = new \Okneloper\Forms\Element('test');
        $el->val('34');
        $this->assertEquals('34', $el->val());
        $this->assertEquals('34', $el->attr('value'));

        $el->attr('value', '55');
        $this->assertEquals('55', $el->val());
        $this->assertEquals('55', $el->attr('value'));
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

    /**
     * Confirm for backwards compatibility
     */
    public function testHasPlaceholderFunction()
    {
        $el = new \Okneloper\Forms\Element('test');

        $el->placeholder();
    }

    /**
     * Confirm for backwards compatibility
     */
    public function testHasDisabledFunction()
    {
        $el = new \Okneloper\Forms\Element('test');

        $this->assertNull($el->attr('disabled'));

        $this->assertNull($el->disabled());
        // confirm nothing changed
        $this->assertNull($el->attr('disabled'));

        $el->disabled(true);
        $this->assertTrue($el->disabled());
        $this->assertTrue($el->attr('disabled'));
    }
}
