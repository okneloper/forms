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
}
