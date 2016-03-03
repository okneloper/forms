<?php

/**
 *
 * @coversDefaultClass \Okneloper\Forms\Elements\Textarea
 */
class TextareaTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::render
     */
    public function testRenders()
    {
        $textarea = new \Okneloper\Forms\Elements\Textarea('area');
        // test rendering empty textarea with default attributes
        $this->assertEquals('<textarea id="area" class="text" name="area"></textarea>', $textarea->render());
    }

    /**
     * @covers ::render
     */
    public function testEscapesValue()
    {
        $textarea = new \Okneloper\Forms\Elements\Textarea('area');
        $textarea->val('<script type="text/javascript">');
        // test rendering empty textarea with default attributes
        $this->assertEquals('<textarea id="area" class="text" name="area">&lt;script type=&quot;text/javascript&quot;&gt;</textarea>', $textarea->render());
    }
}
