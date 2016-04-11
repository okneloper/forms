<?php

/**
 * @coversDefaultClass \Okneloper\Forms\Elements\Date
 */
class DateTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValueIsNull()
    {
        $el = new \Okneloper\Forms\Elements\Date('test');
        $this->assertSame(null, $el->val());
    }

    /**
     * @covers ::getFormat
     * @covers ::setFormat
     * @covers ::getInputFormat
     * @covers ::setInputFormat
     */
    public function testSetsGetsFormat()
    {
        $el = new \Okneloper\Forms\Elements\Date('test');
        $el->setFormat('dmy');
        $this->assertEquals('dmy', $el->getFormat());

        $el->setFormat('dmy', 'd.m.Y');
        $this->assertEquals('d.m.Y', $el->getInputFormat());

        $el->setInputFormat('Y-m--d');
        $this->assertEquals('Y-m--d', $el->getInputFormat());
    }

    /**
     * @covers ::val
     */
    public function testReturnsDatetime()
    {
        $el = new \Okneloper\Forms\Elements\Date('test');
        $value = '2016-04-11';
        $el->val($value);
        $this->assertTrue($el->val() instanceof DateTime);
    }

    /**
     * @covers ::buildAttr
     */
    public function testDefaultFormatIsYmd()
    {
        $el = new \Okneloper\Forms\Elements\Date('test');
        $value = '2016-04-11';
        $el->val($value);
        $this->assertEquals($value, $el->val()->format('Y-m-d'));

        $this->assertEquals('<input type="date" name="test" id="test" class="text" value="' . $value . '">', $el->render());
    }

    public function testRendersAccordingToFormat()
    {

    }
}
