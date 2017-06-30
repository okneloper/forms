<?php

/**
 * @coversDefaultClass \Okneloper\Forms\Elements\Checkbox
 */
class CheckboxTest extends PHPUnit_Framework_TestCase
{
    public function testReturnsNullIfNotSet()
    {
        $checkbox = new \Okneloper\Forms\Elements\Checkbox('terms');
        $this->assertSame(null, $checkbox->val());
    }

    public function testRendersValueEq1()
    {
        $checkbox = new \Okneloper\Forms\Elements\Checkbox('terms');
        $this->assertNotFalse(strpos($checkbox->render(), 'value="1"'), 'Checkbox doesn\'t render value="1"');
    }

    public function testValueDoesNotChnageWhenValIsCalled()
    {
        $checkbox = new \Okneloper\Forms\Elements\Checkbox('terms');
        $checkbox->val('something');
        $this->assertNotFalse(strpos($checkbox->render(), 'value="1"'), 'Checkbox value has changed');
        $this->assertFalse($checkbox->attr('checked'));
        $checkbox->val(1);
        $this->assertTrue($checkbox->attr('checked'));
    }
}
