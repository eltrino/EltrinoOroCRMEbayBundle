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
namespace Eltrino\OroCrmEbayBundle\Tests\Unit\Ebay;

use Eltrino\OroCrmEbayBundle\Ebay\Client\Request;
use Eltrino\OroCrmEbayBundle\Ebay\DefaultAuthorizationHandler;
use Eltrino\OroCrmEbayBundle\Ebay\RestClient;
use Guzzle\Http\ClientInterface;
use PHPUnit\Framework\TestCase;

class RestClientTest extends TestCase
{
    /** @var RestClient */
    protected $object;

    /** @var \PHPUnit_Framework_MockObject_MockObject|ClientInterface */
    protected $client;

    /** @var \PHPUnit_Framework_MockObject_MockObject|DefaultAuthorizationHandler */
    protected $authHandler;

    public function setUp()
    {
        $this->client = $this->getMockBuilder('Guzzle\Http\ClientInterface')->getMock();
        $this->authHandler = $this->getMockBuilder('Eltrino\OroCrmEbayBundle\Ebay\Api\AuthorizationHandler')->getMock();
        $this->object = new RestClient($this->client, $this->authHandler);
    }

    public function testSendRequest()
    {
        $checkAction = RestClient::GET_SERVICE_STATUS;
        $getOrdersAction = RestClient::GET_SERVICE_STATUS;

        $reflection       = new \ReflectionClass($this->object);
        $requestsCounters = $reflection->getProperty('requestsCounters');
        $requestsCounters->setAccessible(true);

        $checkRequest     = new Request($checkAction);
        $getOrdersRequest = new Request($getOrdersAction);

        $request = $this->getMockBuilder('Guzzle\Http\Message\EntityEnclosingRequestInterface')
            ->getMock();

        $request
            ->expects($this->exactly(2))
            ->method('send')
            ->willReturn('test');

        $this->client
            ->expects($this->any())
            ->method('post')
            ->willReturn($request);

        $this->assertEquals(
            'test',
            $this->object->sendRequest($checkRequest)
        );

        $this->assertEquals(
            'test',
            $this->object->sendRequest($getOrdersRequest)
        );
    }
}
