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
namespace Eltrino\OroCrmEbayBundle\Tests\Unit\Provider\Iterator\Order;

use Eltrino\OroCrmEbayBundle\Ebay\Filters\Filter;
use Eltrino\OroCrmEbayBundle\Ebay\RestClient;
use Eltrino\OroCrmEbayBundle\Provider\Iterator\Order\OrderLoader;
use Guzzle\Http\Message\Response;
use PHPUnit\Framework\TestCase;

class OrderLoaderTest extends TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|RestClient */
    protected $client;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Filter */
    protected $firstFilter;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Response */
    protected $response;

    /** @var OrderLoader */
    protected $object;

    protected function setUp()
    {
        $this->client   = $this
            ->getMockBuilder('Eltrino\OroCrmEbayBundle\Ebay\RestClient')
            ->disableOriginalConstructor()
            ->getMock();

        $this->response = $this
            ->getMockBuilder('Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $this->client
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn($this->response);

        $this->firstFilter = $this
            ->getMockBuilder('Eltrino\OroCrmEbayBundle\Ebay\Filters\Filter')
            ->getMock();

        $this->object = new OrderLoader($this->client, $this->firstFilter);
    }

    public function testLoad()
    {
        $ordersXml     = new \SimpleXMLElement(file_get_contents(__DIR__ . '/../../../Fixtures/GetOrders.xml'));
        $empty         = new \SimpleXMLElement('<el></el>');

        $this->response
            ->expects($this->at(0))
            ->method('xml')
            ->willReturn($empty);

        $this->response
            ->expects($this->at(1))
            ->method('xml')
            ->willReturn($ordersXml);

        $orders = [];
        foreach ($ordersXml->children()->OrderArray->children() as $order) {
            $orders[] = $order;
        }

        $this->assertEquals([], $this->object->load());
        $this->assertEquals($orders, $this->object->load());
    }
}
