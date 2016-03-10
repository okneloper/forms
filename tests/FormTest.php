<?php

/**
 * @coversDefaultClass \Okneloper\Forms\Form
 */
class FormTest extends PHPUnit_Framework_TestCase
{
    /**
     * @throws Exception
     * @covers ::submit
     */
    public function testSanitizesStringByDefault()
    {
        $form = new \Okneloper\Forms\Form();
        $form->add('text', 'test');
        $form->submit([
            'test' => '<script>script goes here</script>',
        ]);
        $this->assertEquals('script goes here', $form->val('test'));
    }
}
