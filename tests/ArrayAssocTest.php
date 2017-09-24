<?php

use Okneloper\Forms\Elements\ArrayAssoc;

class ArrayAssocTest extends TestCase
{
    public function testItCanBeCreated()
    {
        $form = $this->makeForm();

        $el = $form->add('arrayAssoc', 'array');

        $this->assertInstanceOf('Okneloper\Forms\ElementInterface', $el);
        $this->assertInstanceOf('Okneloper\Forms\Elements\ArrayAssoc', $el);
    }

    public function testItSetsElementsNameToArraySyntax()
    {
        $array = new ArrayAssoc('test');

        $text = new \Okneloper\Forms\Elements\Text('name');

        $array->addElement($text);

        // name remains `name`
        $this->assertEquals('name', $text->name);
        // but the actual attribute is set to the array syntax
        $this->assertEquals('test[name]', $text->nameAttribute);
    }

    public function testItDoesNotChangeElementsIdAttribute()
    {
        $array = new ArrayAssoc('test');

        $text = new \Okneloper\Forms\Elements\Text('name');

        $array->addElement($text);

        $this->assertEquals('name', $text->id);
    }

    public function testFormCanBeSubmitted()
    {
        $form = $this->makeForm();

        $form->add('text', 'name');

        $array = new ArrayAssoc('brother');
        $array->addElement($form->makeElement('text', 'name'));

        $form->addElement($array);

        $form->submit([
            'name' => 'John',
            'brother' => [
                'name' => 'Jack',
            ],
        ]);

        $this->assertEquals([
            'name' => 'John',
            'brother' => [
                'name' => 'Jack',
            ],
        ], $form->modelToArray());
    }
}
