<?php

namespace RandomStuff\Tests\Functional;

class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetCollection()
    {
        // tests data
        $size = 2;
        $firstUser = array('firstName' => 'lala', 'lastName' => 'lolo', 'seed' => 42);
        $secondUser = array('firstName' => 'joe', 'lastName' => 'lafrite', 'seed' => 24);

        // setup the generator
        $generator = $this->getMockBuilder('\RandomStuff\Generator\AbstractGenerator')
            ->disableOriginalConstructor()
            ->setMethods(array('getOne'))
            ->getMock();
        $generator->expects($this->at(0))
            ->method('getOne')
            ->with(
                $this->equalTo(null), // no seed is given
                $this->equalTo(array())
            )
            ->will($this->returnValue($firstUser));
        $generator->expects($this->at(1))
            ->method('getOne')
            ->with(
                $this->equalTo(null), // no seed is given
                $this->equalTo(array())
            )
            ->will($this->returnValue($secondUser));

        // tests
        $collection = $generator->getCollection($size);
        $this->assertSame(array($firstUser, $secondUser), $collection);
    }
}
