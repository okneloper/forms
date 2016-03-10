<?php
use Okneloper\Forms\Filters\EmptyStringToNullFilter;

/**
 * @coversDefaultClass Okneloper\Forms\Filters\EmptyStringToNullFilter
 */
class EmptyStringToNullFilterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::filter
     */
    public function testDoesntFilter()
    {
        $filter = new EmptyStringToNullFilter();
        $this->assertSame(null, $filter->filter('test', ''));

        $specialValues = [
            null, 0, 'test',
        ];
        foreach ($specialValues as $value) {
            $this->assertSame($value, $filter->filter('test', $value));
        }
    }
}
