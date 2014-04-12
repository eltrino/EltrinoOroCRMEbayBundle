<?php
/**
 * Created by PhpStorm.
 * User: psw
 * Date: 4/11/14
 * Time: 5:38 PM
 */

namespace Eltrino\EbayBundle\Tests\Provider\Iterator;

use Eltrino\EbayBundle\Provider\Iterator\EbayDataIterator;

class EbayDataIteratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EbayDataIterator
     */
    private $iterator;

    /**
     * @var Loader
     */
    private $loader;

    protected function setUp()
    {
        $this->loader = $this
            ->getMockBuilder('Eltrino\EbayBundle\Provider\Iterator\Loader')
            ->getMock();

        $this->iterator = new EbayDataIterator($this->loader);
    }

    public function testKey()
    {
        $this->assertEquals(0, $this->iterator->key());
        $this->iterator->next();
        $this->iterator->next();
        $this->assertEquals(2, $this->iterator->key());
    }

    public function testNext()
    {
        $this->assertEquals(0, $this->iterator->key());
        $this->iterator->next();
        $this->assertEquals(1, $this->iterator->key());
    }

    public function testRewind()
    {
        $this->iterator->next();
        $this->iterator->next();
        $this->assertEquals(2, $this->iterator->key());
        $this->iterator->rewind();
        $this->assertEquals(0, $this->iterator->key());
    }

    public function testValidWhenElementsArrayIsEmpty()
    {
        $this->loader
            ->expects($this->once())
            ->method('load')
            ->will($this->returnValue(array()));

        $this->assertFalse($this->iterator->valid());
    }

    public function testValid()
    {
        $elements = [
            new \SimpleXMLElement('<order><id>1</id></order>'),
            new \SimpleXMLElement('<order><id>2</id></order>')
        ];

        $this->loader
            ->expects($this->at(0))
            ->method('load')
            ->will($this->returnValue($elements));

        $this->loader
            ->expects($this->at(1))
            ->method('load')
            ->will($this->returnValue(array()));

        $this->assertTrue($this->iterator->valid());
        $this->iterator->next();
        $this->assertTrue($this->iterator->valid());
        $this->iterator->next();
        $this->assertFalse($this->iterator->valid());
    }

    public function testCurrentWhenElementsArrayIsEmpty()
    {
        $this->loader
            ->expects($this->once())
            ->method('load')
            ->will($this->returnValue(array()));

        $this->assertNull($this->iterator->current());
    }

    public function testCurrent()
    {
        $elements1 = [
            new \SimpleXMLElement('<order><id>1</id></order>'),
            new \SimpleXMLElement('<order><id>2</id></order>')
        ];

        $elements2 = [
            new \SimpleXMLElement('<order><id>3</id></order>'),
            new \SimpleXMLElement('<order><id>4</id></order>')
        ];

        $this->loader
            ->expects($this->at(0))
            ->method('load')
            ->will($this->returnValue($elements1));

        $this->loader
            ->expects($this->at(1))
            ->method('load')
            ->will($this->returnValue($elements2));

        $this->loader
            ->expects($this->at(2))
            ->method('load')
            ->will($this->returnValue(array()));

        $elm1 = $this->iterator->current();
        $this->iterator->next();
        $elm2 = $this->iterator->current();
        $this->iterator->next();
        $elm3 = $this->iterator->current();
        $this->iterator->next();
        $elm4 = $this->iterator->current();

        $this->assertInstanceOf('SimpleXmlElement', $elm1);
        $this->assertInstanceOf('SimpleXmlElement', $elm2);

        $this->assertNotEquals($elm1, $elm2);

        $this->assertNotNull($elm3);
        $this->assertNotNull($elm4);

        $this->iterator->next();
        $this->assertNull($this->iterator->current());
    }
}
