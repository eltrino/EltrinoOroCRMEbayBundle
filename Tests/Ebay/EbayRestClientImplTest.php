<?php
/**
 * Created by PhpStorm.
 * User: psw
 * Date: 3/21/14
 * Time: 4:40 PM
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
