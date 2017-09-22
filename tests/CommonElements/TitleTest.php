<?php
/**
 * @author Aleksey Lavrinenko <aleksey.lavrinenko@mtcmedia.co.uk>
 * Created on 22.09.2017.
 */

use Okneloper\Forms\CommonElements\Title;

class TitleTest extends PHPUnit_Framework_TestCase
{
    public function testItHad5Options()
    {
        $el = new Title('test');
        $this->assertCount(5, $el->options());
    }
}
