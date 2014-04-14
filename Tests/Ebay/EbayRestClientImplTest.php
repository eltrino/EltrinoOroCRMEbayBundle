<?php
/**
 * Created by PhpStorm.
 * User: psw
 * Date: 3/21/14
 * Time: 4:40 PM
 */

namespace Eltrino\OroCrmEbayBundle\Tests\Ebay;Eltrino\OroCrmEbayBundle\undle\Ebay\EbayRestClientImpl;

class EbayRestClientImplTest extends \PHPUnit_Framework_TestCase
{
    public function testGetOrderRestClient()
    {
        $client = $this->getMockBuilder('Guzzle\Http\ClientInterface')
            ->getMock();
        $authHandler = $this->getMEltrino\OroCrmEbayBundle\\EbayBundle\Ebay\Api\AuthorizationHandler')
            ->getMock();
        $ebayRestClient = new EbayRestClientImpl($client, $authHandler);
        $this->assertInstancEltrino\OroCrmEbayBundle\ltrino\EbayBundle\Ebay\Api\OrderRestClient',
            $ebayRestClient->getOrderRestClient()
        );
    }
}
