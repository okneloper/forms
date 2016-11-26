<?php
use Okneloper\Forms\Filters\NativeFilter;

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
            'test' => ' <script>script goes here</script> x ',
        ]);
        // we expect the default filter to remove tags and trim the input string
        $this->assertEquals('script goes here x', $form->val('test'));
    }

    public function testDoesNotEncodeCharacters()
    {
        $form = new \Okneloper\Forms\Form();
        $form->add('text', 'test');
        $form->submit([
            'test' => ' script \'goes\' here => "x" ',
        ]);
        // we expect the default filter to remove tags and trim the input string
        $this->assertEquals('script \'goes\' here => "x"', $form->val('test'));
    }

    public function testAppliesArrayOdFilters()
    {
        $filter1 = new NativeFilter(FILTER_SANITIZE_STRING);
        $filter2 = new NativeFilter(FILTER_SANITIZE_NUMBER_INT);

        $input = 'x11';

        $filtered = $filter1->filter('test', $input);
        $filtered = $filter2->filter('test', $filtered);

        $form = new \Okneloper\Forms\Form();
        $form->add('text', 'test');

        $form->setFilters([
            'test' => [$filter1, $filter2],
        ]);

        $form->submit(['test' => $input]);

        $this->assertSame($filtered, $form->val('test'));
    }
}
