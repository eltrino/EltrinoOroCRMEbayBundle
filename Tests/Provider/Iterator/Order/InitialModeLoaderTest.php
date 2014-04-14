<?php
/**
 * Created by PhpStorm.
 * User: psw
 * Date: 4/11/14
 * Time: 5:36 PM
 */

namespace Eltrino\OroCrmEbayBundle\Tests\Provider\Iterator\Order;Eltrino\OroCrmEbayBundle\undle\Provider\Iterator\Order\InitialModeLoader;

class InitialModeLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var InitialModeLoader
     */
    private $loader;

    protected function setUp()
    {
        $action = $this
            ->getMEltrino\OroCrmEbayBundle\\EbayBundle\Provider\Actions\Action')
            ->getMock();
        $filtersFactory = $this
            Eltrino\OroCrmEbayBundle\ltrino\EbayBundle\Ebay\Filters\FiltersFactory')
            ->getMock();
        $compositeFilter = $this
      Eltrino\OroCrmEbayBundle\der('Eltrino\EbayBundle\Ebay\Filters\CompositeFilter')
            ->getMock();
        $createTimeFilter = $this
Eltrino\OroCrmEbayBundle\ckBuilder('Eltrino\EbayBundle\Ebay\Filters\CreateTimeRangeFilter')
            ->disableOriginalConstructor()
            ->getMock();
        $pagerFilter = Eltrino\OroCrmEbayBundle\>getMockBuilder('Eltrino\EbayBundle\Ebay\Filters\PagerFilter')
            ->disableOriginalConstructor()
            ->getMock();

        $elements = [
            new \SimpleXMLElement('<orderId>1</orderId>'),
            new \SimpleXMLElement('<orderId>2</orderId>')
        ];

        $action
            ->expects($this->at(0))
            ->method('execute')
            ->with($this->equalTo($compositeFilter))
            ->will($this->returnValue($elements));

        $action
            ->expects($this->at(1))
            ->method('execute')
            ->with($this->equalTo($compositeFilter))
            ->will($this->returnValue(array()));

        $filtersFactory
            ->expects($this->once())
            ->method('createCompositeFilter')
            ->will($this->returnValue($compositeFilter));

        $filtersFactory
            ->expects($this->exactly(3))
            ->method('createCreateTimeRangeFilter')
            ->will($this->returnValue($createTimeFilter));

        $filtersFactory
            ->expects($this->exactly(3))
            ->method('createPagerFilter')
            ->will($this->returnValue($pagerFilter));

        $startSycDate = new \DateTime('now');
        $startSycDate->sub(new \DateInterval('P95D')); // Create Time Range has 90 days interval. As a result loader should try to load 2 times with two dates interval

        $this->loader = new InitialModeLoader($action, $filtersFactory, $startSycDate);
    }

    public function testLoad()
    {
        $elements = $this->loader->load();
        $this->assertNotEmpty($elements);
        $this->assertCount(2, $elements);
        $this->assertEquals($elements[0], new \SimpleXMLElement('<orderId>1</orderId>'));

        $elements = $this->loader->load();
        $this->assertEmpty($elements);
    }
}
