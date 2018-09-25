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

    public function testCanSetCustomValue()
    {
        $checkbox = new \Okneloper\Forms\Elements\Checkbox('terms');
        $checkbox->setValue('Yes');
        $this->assertContains('value="Yes"', $checkbox->render());
    }

    public function testValueDoesNotChnageWhenValIsCalled()
    {
        $checkbox = new \Okneloper\Forms\Elements\Checkbox('terms');
        $checkbox->val('something');
        $this->assertNotFalse(strpos($checkbox->render(), 'value="1"'), 'Checkbox value has changed');
        $this->assertFalse($checkbox->attr('checked'));
        $this->assertNull($checkbox->val());
        $checkbox->val('1');
        $this->assertTrue($checkbox->attr('checked'));
        $this->assertSame('1', $checkbox->val());
    }

    public function testReturnsNullWhenNotChecked()
    {
        $checkbox = new \Okneloper\Forms\Elements\Checkbox('terms');
        $this->assertNull($checkbox->val());
    }

    public function testReturnsCustomValueWhenNotChecked()
    {
        $checkbox = new \Okneloper\Forms\Elements\Checkbox('terms');
        $checkbox->setValueFalse('nope');
        $this->assertSame('nope', $checkbox->val());
        $checkbox->val(1);
        $this->assertSame('1', $checkbox->val());
    }

    public function testDoesNotChangeWhenDisabled()
    {
        $checkbox = new \Okneloper\Forms\Elements\Checkbox('terms');
        $this->assertNull($checkbox->val());
        $checkbox->disable();
        $checkbox->val('1');
        $this->assertNull($checkbox->val());
    }

    public function testDoesNotChangeWhenReadonly()
    {
        $checkbox = new \Okneloper\Forms\Elements\Checkbox('terms');
        $this->assertNull($checkbox->val());
        $checkbox->attr('readonly', true);
        $checkbox->val('1');
        $this->assertNull($checkbox->val());
    }

    public function testForceValueDoesNotResetValue()
    {
        $checkbox = new \Okneloper\Forms\Elements\Checkbox('terms');
        $checkbox->setValue('SomeValue');
        $checkbox->forceValue(null);
        $this->assertEquals('SomeValue', $checkbox->attr('value'));
        $checkbox->forceValue('');
        $this->assertEquals('SomeValue', $checkbox->attr('value'));
    }

    public function testCheckedIfUpdatesModel()
    {
        $model = new \Okneloper\Forms\Model();
        $form = new \Okneloper\Forms\Form($model);
        $form->add('checkbox', 'terms')->setValueFalse(0);

        $form->submit([]);

        $this->assertSame(0, $model->terms);
    }
}
