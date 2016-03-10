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
        $this->assertEquals('<script>script goes here</script>', $filter->filter('test', '<script>script goes here</script>'));

        $specialValues = [
            null, '', 0, 'test',
        ];
        foreach ($specialValues as $value) {
            $this->assertSame($value, $filter->filter('test', $value));
        }
    }
}
