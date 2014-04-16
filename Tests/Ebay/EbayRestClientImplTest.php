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

use Eltrino\OroCrmEbayBundle\Ebay\EbayRestClientImpl;

class EbayRestClientImplTest extends \PHPUnit_Framework_TestCase
{
    public function testGetOrderRestClient()
    {
        $client = $this->getMockBuilder('Guzzle\Http\ClientInterface')
            ->getMock();
        $authHandler = $this->getMockBuilder('Eltrino\OroCrmEbayBundle\Ebay\Api\AuthorizationHandler')
            ->getMock();
        $ebayRestClient = new EbayRestClientImpl($client, $authHandler);
        $this->assertInstanceOf(
            'Eltrino\OroCrmEbayBundle\Ebay\Api\OrderRestClient',
            $ebayRestClient->getOrderRestClient()
        );
    }

    public function testGetCheckRestClient()
    {
        $client = $this->getMockBuilder('Guzzle\Http\ClientInterface')
            ->getMock();
        $authHandler = $this->getMockBuilder('Eltrino\OroCrmEbayBundle\Ebay\Api\AuthorizationHandler')
            ->getMock();
        $ebayRestClient = new EbayRestClientImpl($client, $authHandler);
        $this->assertInstanceOf(
            'Eltrino\OroCrmEbayBundle\Ebay\Api\CheckRestClient',
            $ebayRestClient->getCheckRestClient()
        );
    }
}
