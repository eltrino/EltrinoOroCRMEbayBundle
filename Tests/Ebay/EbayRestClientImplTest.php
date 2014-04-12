<?php
/**
 * Created by PhpStorm.
 * User: psw
 * Date: 3/21/14
 * Time: 4:40 PM
 */

namespace Eltrino\EbayBundle\Tests\Ebay;

use Eltrino\EbayBundle\Ebay\EbayRestClientImpl;

class EbayRestClientImplTest extends \PHPUnit_Framework_TestCase
{
    public function testGetOrderRestClient()
    {
        $client = $this->getMockBuilder('Guzzle\Http\ClientInterface')
            ->getMock();
        $authHandler = $this->getMockBuilder('Eltrino\EbayBundle\Ebay\Api\AuthorizationHandler')
            ->getMock();
        $ebayRestClient = new EbayRestClientImpl($client, $authHandler);
        $this->assertInstanceOf(
            'Eltrino\EbayBundle\Ebay\Api\OrderRestClient',
            $ebayRestClient->getOrderRestClient()
        );
    }
}
