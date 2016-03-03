<?php

/**
 * @author Aleksey Lavrinenko
 *
 * @coversDefaultClass \Okneloper\Forms\Filters\DateTransformFilter
 *
 */
class DateTransformFilterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::filter
     * @covers ::__construct
     */
    public function testFiltersValidDate()
    {
        $filter = new \Okneloper\Forms\Filters\DateTransformFilter('d/m/Y', 'Y-m-d');
        $this->assertEquals('2016-03-03', $filter->filter('date', '03/03/2016'));

        $filter = new \Okneloper\Forms\Filters\DateTransformFilter('d/m/Y', 'd.m.Y');
        $this->assertEquals('03.03.2016', $filter->filter('date', '03/03/2016'));
    }

    /**
     * @covers ::filter
     */
    public function testDefaultToFormatIsISO()
    {
        $filter = new \Okneloper\Forms\Filters\DateTransformFilter('d/m/Y');
        $this->assertEquals('2016-03-03', $filter->filter('date', '03/03/2016'));
    }

    /**
     * @covers ::filter
     */
    public function testFiltersInvalidDate()
    {
        $filter = new \Okneloper\Forms\Filters\DateTransformFilter('d/m/Y');
        $this->assertEquals('xxx', $filter->filter('date', 'xxx'));
    }
}
