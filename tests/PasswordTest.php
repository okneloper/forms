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

    public function testItDoesNotRenderValue()
    {
        $password = new \Okneloper\Forms\Elements\Password('password');
        $password->val('password');

        $this->assertNotContains('value="password"', $password->render());
    }
}
