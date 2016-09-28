<?php

/**
 * @author Aleksey Lavrinenko
 * @version 29.09.2016.
 */
class PasswordTest extends PHPUnit_Framework_TestCase
{
    public function testDoesNotTransformValue()
    {
        $passwordString = ' <script>alert("Raw")</script> ';

        $form = new \Okneloper\Forms\Form();
        $form->add('password', 'test');
        $form->submit([
            'test' => $passwordString,
        ]);
        $this->assertEquals($passwordString, $form->val('test'));
    }
}
