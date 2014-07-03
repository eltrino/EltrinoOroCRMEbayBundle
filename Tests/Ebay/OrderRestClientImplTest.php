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
use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

class OrderRestClientImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Guzzle\Http\ClientInterface
     * @Mock Guzzle\Http\ClientInterface
     */
    private $client;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Ebay\Api\AuthorizationHandler
     * @Mock Eltrino\OroCrmEbayBundle\Ebay\Api\AuthorizationHandler
     */
    private $authHandler;

    /**
     * @var \Guzzle\Http\Message\RequestInterface
     * @Mock Guzzle\Http\Message\RequestInterface
     */
    private $request;

    /**
     * @var \Guzzle\Http\Message\Response
     * @Mock Guzzle\Http\Message\Response
     */
    private $response;

    /**
     * @var \SimpleXmlElement
     */
    private $responseXml;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Ebay\Filters\Filter
     * @Mock Eltrino\OroCrmEbayBundle\Ebay\Filters\Filter
     */
    private $filter;

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
        MockAnnotations::init($this);

        $this->responseXml = new \SimpleXMLElement('<GetOrdersResponse xmlns="urn:ebay:apis:eBLBaseComponents"><OrderArray><Order><OrderId>1</OrderId></Order><Order><OrderId>2</OrderId></Order></OrderArray></GetOrdersResponse>');
        $this->parsedResponseArray = [
            new \SimpleXMLElement('<Order><OrderId>1</OrderId></Order>'),
            new \SimpleXMLElement('<Order><OrderId>2</OrderId></Order>')
        ];

        $this->orderRestClient = new OrderRestClientImpl($this->client, $this->authHandler);
    }

    public function testGetOrders()
    {
        $body = '<ParsedRequestBody><RequestFilters>1</RequestFilters></ParsedRequestBody>';

        $this->filter->expects($this->once())
            ->method('process')
            ->with($this->isType(\PHPUnit_Framework_Constraint_IsType::TYPE_STRING))
            ->will($this->returnValue($body));

        $this->client->expects($this->once())
            ->method('post')
            ->with($this->isNull(), $this->logicalAnd(
                    $this->isType(\PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY),
                    $this->arrayHasKey('X-EBAY-API-CALL-NAME'),
                    $this->callback(function($other) { return $other['X-EBAY-API-CALL-NAME'] == 'GetOrders'; })
            ), $this->equalTo($body))
            ->will($this->returnValue($this->request));

        $this->request->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->response));

        $this->response->expects($this->once())
            ->method('xml')
            ->will($this->returnValue($this->responseXml));

        $orders = $this->orderRestClient->getOrders($this->filter);

        $this->assertCount(2, $orders);
        $this->assertXmlStringEqualsXmlString($this->parsedResponseArray[0]->asXml(), $orders[0]->asXml());
        $this->assertXmlStringEqualsXmlString($this->parsedResponseArray[1]->asXml(), $orders[1]->asXml());
    }
}
