<?php
/*
 * Copyright (c) 2014 Eltrino LLC (http://eltrino.com)
 *
 * Licensed under the Open Software License (OSL 3.0).
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://opensource.org/licenses/osl-3.0.php
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@eltrino.com so we can send you a copy immediately.
 */
namespace Eltrino\OroCrmEbayBundle\Tests\Provider\Iterator\Order;

use Eltrino\OroCrmEbayBundle\Provider\Iterator\Order\UpdateModeLoader;

class UpdateModeLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UpdateModeLoader
     */
    private $loader;

    protected function setUp()
    {
        $action = $this
            ->getMockBuilder('Eltrino\OroCrmEbayBundle\Provider\Actions\Action')
            ->getMock();
        $filtersFactory = $this
            ->getMockBuilder('Eltrino\OroCrmEbayBundle\Ebay\Filters\FiltersFactory')
            ->getMock();
        $compositeFilter = $this
            ->getMockBuilder('Eltrino\OroCrmEbayBundle\Ebay\Filters\CompositeFilter')
            ->getMock();
        $createTimeFilter = $this
            ->getMockBuilder('Eltrino\OroCrmEbayBundle\Ebay\Filters\CreateTimeRangeFilter')
            ->disableOriginalConstructor()
            ->getMock();
        $modTimeFilter = $this
            ->getMockBuilder('Eltrino\OroCrmEbayBundle\Ebay\Filters\ModTimeRangeFilter')
            ->disableOriginalConstructor()
            ->getMock();
        $pagerFilter = $this
            ->getMockBuilder('Eltrino\OroCrmEbayBundle\Ebay\Filters\PagerFilter')
            ->disableOriginalConstructor()
            ->getMock();

        $elements = [
            new \SimpleXMLElement('<orderId>1</orderId>'),
            new \SimpleXMLElement('<orderId>2</orderId>')
        ];

        $elementsForUpdate = [
            new \SimpleXMLElement('<orderId>2</orderId>'),
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

        $action
            ->expects($this->at(2))
            ->method('execute')
            ->with($this->equalTo($compositeFilter))
            ->will($this->returnValue($elementsForUpdate));

        $filtersFactory
            ->expects($this->once())
            ->method('createCompositeFilter')
            ->will($this->returnValue($compositeFilter));

        $filtersFactory
            ->expects($this->exactly(2))
            ->method('createCreateTimeRangeFilter')
            ->will($this->returnValue($createTimeFilter));

        $filtersFactory
            ->expects($this->exactly(3))
            ->method('createModTimeRangeFilter')
            ->will($this->returnValue($modTimeFilter));

        $filtersFactory
            ->expects($this->exactly(5))
            ->method('createPagerFilter')
            ->will($this->returnValue($pagerFilter));

        $startSycDate = new \DateTime('now');
        $startSycDate->sub(new \DateInterval('P35D')); // Mod Time Range has 30 days interval. As a result loader should try to load 2 times with two dates interval

        $this->loader = new UpdateModeLoader($action, $filtersFactory, $startSycDate);
    }

    public function testLoad()
    {
        $elements = $this->loader->load();
        $this->assertNotEmpty($elements);
        $this->assertCount(2, $elements);
        $this->assertEquals($elements[0], new \SimpleXMLElement('<orderId>1</orderId>'));

        $elementsForUpdate = $this->loader->load();
        $this->assertNotEmpty($elementsForUpdate);

        $elements = $this->loader->load();
        $this->assertEmpty($elements);
    }
}
