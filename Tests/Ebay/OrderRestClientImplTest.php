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
namespace Eltrino\OroCrmEbayBundle\Tests\Ebay;

use Eltrino\OroCrmEbayBundle\Ebay\OrderRestClientImpl;

class OrderRestClientImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Guzzle\Http\ClientInterface
     */
    private $client;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Ebay\Api\AuthorizationHandler
     */
    private $authHandler;

    /**
     * @var \Guzzle\Http\Message\RequestInterface
     */
    private $request;

    /**
     * @var \Guzzle\Http\Message\Response
     */
    private $response;

    /**
     * @var \SimpleXmlElement
     */
    private $responseXml;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Ebay\Filters\Filter
     */
    private $Filter;

    /**
     * @var array
     */
    private $parsedResponseArray;

    /**
     * @var OrderRestClientImpl
     */
    private $orderRestClient;

    /**
     * @var string
     */
    private $bodyString;

    public function setUp()
    {
        $this->client = $this->getMockBuilder('Guzzle\Http\ClientInterface')
            ->getMock();
        $this->authHandler = $this->getMockBuilder('Eltrino\OroCrmEbayBundle\Ebay\Api\AuthorizationHandler')
            ->getMock();
        $this->request = $this->getMockBuilder('Guzzle\Http\Message\RequestInterface')
            ->getMock();
        $this->response = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $this->responseXml = new \SimpleXMLElement('<GetOrdersResponse xmlns="urn:ebay:apis:eBLBaseComponents"><OrderArray><Order><OrderId>1</OrderId></Order><Order><OrderId>2</OrderId></Order></OrderArray></GetOrdersResponse>');
        $this->parsedResponseArray = [
            new \SimpleXMLElement('<Order><OrderId>1</OrderId></Order>'),
            new \SimpleXMLElement('<Order><OrderId>2</OrderId></Order>')
        ];

        $this->orderRestClient = new OrderRestClientImpl($this->client, $this->authHandler);
        $this->Filter = $this->createFilter();

        $this->bodyString = '<?xml version="1.0" encoding="utf-8"?>
        <GetOrdersRequest xmlns="urn:ebay:apis:eBLBaseComponents">
            <RequesterCredentials>
            <eBayAuthToken>' . $this->authHandler->getAuthToken() . '</eBayAuthToken>
            </RequesterCredentials>
            <CreateTimeFrom>2014-01-01</CreateTimeFrom>
            <CreateTimeTo>2015-01-01</CreateTimeTo>
            <OrderRole>Seller</OrderRole>
            <OrderStatus>Completed</OrderStatus>
            <Pagination>
            <EntriesPerPage>100</EntriesPerPage>
            <PageNumber>1</PageNumber>
            </Pagination>
            <SortingOrder>Ascending</SortingOrder>
        </GetOrdersRequest>';
    }

    public function testGetOrders()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->will($this->returnValue($this->request));

        $this->request->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->response));

        $this->response->expects($this->once())
            ->method('xml')
            ->will($this->returnValue($this->responseXml));

        $orders = $this->orderRestClient->getOrders($this->Filter);

        $this->assertCount(2, $orders);
        $this->assertXmlStringEqualsXmlString($this->parsedResponseArray[0]->asXml(), $orders[0]->asXml());
        $this->assertXmlStringEqualsXmlString($this->parsedResponseArray[1]->asXml(), $orders[1]->asXml());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createFilter()
    {
        return $this->getMockBuilder('Eltrino\OroCrmEbayBundle\Ebay\Filters\Filter')
            ->getMock();
    }
}
