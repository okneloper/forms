<?php
/**
 * Created by PhpStorm.
 * User: al
 * Date: 26.09.2018
 * Time: 23:02
 */

use Okneloper\Forms\Validation\Illuminate\IlluminateValidatorResolver;

class IlluminateValidatorResolverTest extends PHPUnit_Framework_TestCase
{
    public function testCanCreateInstance()
    {
        $resolver = new IlluminateValidatorResolver(new \Illuminate\Container\Container(), ['name' => 'required']);
        $this->assertInstanceOf(IlluminateValidatorResolver::class, $resolver);
    }

    public function testResolves()
    {
        $resolver = new IlluminateValidatorResolver(new \Illuminate\Container\Container(), ['name' => 'required']);
        $validator = $resolver->resolve(new \Okneloper\Forms\Form());
        $this->assertInstanceOf(\Okneloper\Forms\Validation\Illuminate\IlluminateValidator::class, $validator);
    }
}
