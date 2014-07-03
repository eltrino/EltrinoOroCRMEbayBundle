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
use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

class UpdateModeLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Eltrino\OroCrmEbayBundle\Ebay\Api\EbayRestClient
     * @Mock Eltrino\OroCrmEbayBundle\Ebay\Api\EbayRestClient
     */
    private $ebayRestClient;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Ebay\Api\OrderRestClient
     * @Mock Eltrino\OroCrmEbayBundle\Ebay\Api\OrderRestClient
     */
    private $ebayOrdersClient;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Ebay\Filters\FiltersFactory
     * @Mock Eltrino\OroCrmEbayBundle\Ebay\Filters\FiltersFactory
     */
    private $filtersFactory;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Ebay\Filters\CompositeFilter
     * @Mock Eltrino\OroCrmEbayBundle\Ebay\Filters\CompositeFilter
     */
    private $compositeFilter;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Ebay\Filters\CreateTimeRangeFilter
     * @Mock Eltrino\OroCrmEbayBundle\Ebay\Filters\CreateTimeRangeFilter
     */
    private $createTimeFilter;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Ebay\Filters\ModTimeRangeFilter
     * @Mock Eltrino\OroCrmEbayBundle\Ebay\Filters\ModTimeRangeFilter
     */
    private $modTimeFilter;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Ebay\Filters\PagerFilter
     * @Mock Eltrino\OroCrmEbayBundle\Ebay\Filters\PagerFilter
     */
    private $pagerFilter;

    /**
     * @var UpdateModeLoader
     */
    private $loader;

    protected function setUp()
    {
        MockAnnotations::init($this);

        $elements = [
            new \SimpleXMLElement('<orderId>1</orderId>'),
            new \SimpleXMLElement('<orderId>2</orderId>')
        ];

        $elementsForUpdate = [
            new \SimpleXMLElement('<orderId>2</orderId>'),
        ];

        $this->ebayRestClient
            ->expects($this->any())
            ->method('getOrderRestClient')
            ->will($this->returnValue($this->ebayOrdersClient));

        $this->ebayOrdersClient
            ->expects($this->at(0))
            ->method('getOrders')
            ->with($this->equalTo($this->compositeFilter))
            ->will($this->returnValue($elements));

        $this->ebayOrdersClient
            ->expects($this->at(1))
            ->method('getOrders')
            ->with($this->equalTo($this->compositeFilter))
            ->will($this->returnValue(array()));

        $this->ebayOrdersClient
            ->expects($this->at(2))
            ->method('getOrders')
            ->with($this->equalTo($this->compositeFilter))
            ->will($this->returnValue($elementsForUpdate));

        $this->filtersFactory
            ->expects($this->once())
            ->method('createCompositeFilter')
            ->will($this->returnValue($this->compositeFilter));

        $this->filtersFactory
            ->expects($this->exactly(2))
            ->method('createCreateTimeRangeFilter')
            ->will($this->returnValue($this->createTimeFilter));

        $this->filtersFactory
            ->expects($this->exactly(3))
            ->method('createModTimeRangeFilter')
            ->will($this->returnValue($this->modTimeFilter));

        $this->filtersFactory
            ->expects($this->exactly(5))
            ->method('createPagerFilter')
            ->will($this->returnValue($this->pagerFilter));

        $startSycDate = new \DateTime('now');
        $startSycDate->sub(new \DateInterval('P35D')); // Mod Time Range has 30 days interval. As a result loader should try to load 2 times with two dates interval

        $this->loader = new UpdateModeLoader($this->ebayRestClient, $this->filtersFactory, $startSycDate);
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
