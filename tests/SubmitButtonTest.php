<?php

/**
 * @author Aleksey Lavrinenko
 * @version 09.10.2016.
 */
class SubmitButtonTest extends PHPUnit_Framework_TestCase
{
    public function testRendersAttributes()
    {
        $button = new \Okneloper\Forms\Elements\SubmitButton('btn', 'Submit');

        $this->assertEquals('<button type="submit" name="btn" id="btn" value="1">Submit</button>', $button->render());
    }
}
