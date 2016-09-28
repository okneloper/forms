<?php
use Okneloper\Forms\Filters\VoidFilter;

/**
 * @coversDefaultClass Okneloper\Forms\Filters\VoidFilter
 */
class VoidFilterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::filter
     */
    public function testDoesntFilter()
    {
        $filter = new VoidFilter();
        $input = '<script>script goes here</script> ';
        $this->assertEquals($input, $filter->filter('test', $input));

        $specialValues = [
            null, '', 0, 'test',
        ];
        foreach ($specialValues as $value) {
            $this->assertSame($value, $filter->filter('test', $value));
        }
    }
}
