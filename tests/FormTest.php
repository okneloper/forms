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
            'test' => '<script>script goes here</script>',
        ]);
        $this->assertEquals('script goes here', $form->val('test'));
    }

    public function testTrimsStringByDefault()
    {
        $form = new \Okneloper\Forms\Form();
        $form->add('text', 'test');
        $form->submit([
            'test' => 'text with a space at the end ',
        ]);
        $this->assertEquals('text with a space at the end', $form->val('test'));
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

    public function testIgnoreButtonWhenSubmittingData()
    {
        $form = new \Okneloper\Forms\Form();

        $form->add('text', 'fname');
        $form->add('text', 'lname');
        $form->add('submitButton', 'Submit');

        $expectedData = [
            'fname' => 'John',
            'lname' => 'Smith',
        ];

        $data = $expectedData + [
            'Submit' => '1',
        ];
        $form->submit($data);

        $this->assertEquals($expectedData, $form->modelToArray());
    }

    public function testSetsAction()
    {
        $form = new \Okneloper\Forms\Form();
        $expected = '/my/action';
        $form->setAction($expected);
        $this->assertEquals($expected, $form->getAction());
    }
}
