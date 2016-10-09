<?php

class ButtonTest extends PHPUnit_Framework_TestCase
{
    public function testRendersAttributes()
    {
        $button = new \Okneloper\Forms\Elements\Button('btn', 'Submit');

        $this->assertEquals('<button type="button" name="btn" id="btn">Submit</button>', $button->render());
    }
}
