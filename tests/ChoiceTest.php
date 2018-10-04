<?php

use Okneloper\Forms\Elements\Choice;

class ChoiceTest extends PHPUnit_Framework_TestCase
{
    public function testCanResolveOptionsWithClosureThatReturnsIndexedArray()
    {
        $options = [
            [
                'id' => '1',
                'fname' => 'John',
                'lname' => 'Doe',
            ],
            [
                'id' => '2',
                'fname' => 'Jane',
                'lname' => 'Smith',
            ],
        ];

        $el = new Choice('test');
        $el->options($options, function ($option) {
            return [$option['id'], $option['fname']];
        });

        $this->assertEquals([
            '1' => 'John',
            '2' => 'Jane',
        ], $el->options());
    }

    public function testCanResolveOptionsWithClosureThatReturnsAssociativeArray()
    {
        $options = [
            [
                'id' => '1',
                'fname' => 'John',
                'lname' => 'Doe',
            ],
            [
                'id' => '2',
                'fname' => 'Jane',
                'lname' => 'Smith',
            ],
        ];

        $el = new Choice('test');
        $el->options($options, function ($option) {
            return [$option['id'] => $option['fname']];
        });

        $this->assertEquals([
            '1' => 'John',
            '2' => 'Jane',
        ], $el->options());
    }
}
