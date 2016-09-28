<?php

class ModelTest extends PHPUnit_Framework_TestCase
{
    public function testNonexistentFieldDoesNotRaiseNotice()
    {
        $model = new \Okneloper\Forms\Model([]);
        $someField = $model->someField;
    }
}
