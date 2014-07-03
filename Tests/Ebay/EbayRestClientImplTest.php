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
use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

class EbayRestClientImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Eltrino\OroCrmEbayBundle\Ebay\Api\AuthorizationHandler
     * @Mock Eltrino\OroCrmEbayBundle\Ebay\Api\AuthorizationHandler
     */
    private $authHandler;

    /**
     * @var Guzzle\Http\ClientInterface
     * @Mock Guzzle\Http\ClientInterface
     */
    private $client;
    /**
     * @var EbayRestClientImpl
     */
    private $ebayRestClient;

    protected function setUp()
    {
        MockAnnotations::init($this);
        $this->ebayRestClient = new EbayRestClientImpl($this->client, $this->authHandler);
    }

    public function testGetOrderRestClient()
    {
        $this->assertInstanceOf(
            'Eltrino\OroCrmEbayBundle\Ebay\Api\OrderRestClient',
            $this->ebayRestClient->getOrderRestClient()
        );
    }

    public function testGetCheckRestClient()
    {
        $this->assertInstanceOf(
            'Eltrino\OroCrmEbayBundle\Ebay\Api\CheckRestClient',
            $this->ebayRestClient->getCheckRestClient()
        );
    }
}
