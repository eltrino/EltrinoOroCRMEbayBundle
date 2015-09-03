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
namespace Eltrino\OroCrmEbayBundle\Tests\Unit\Ebay\Client;

use Eltrino\OroCrmEbayBundle\Ebay\Client\RestClientFactory;
use Eltrino\OroCrmEbayBundle\Ebay\DefaultAuthorizationHandler;
use Eltrino\OroCrmEbayBundle\Ebay\RestClient;
use Guzzle\Http\Client;

class RestClientFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var RestClientFactory */
    protected $object;

    public function setUp()
    {
        $this->object = new RestClientFactory();
    }

    public function testCreate()
    {
        $restClient = new RestClient(
            new Client('baseUrl'),
            new DefaultAuthorizationHandler('devId', 'appId', 'certId', 'authToken')
        );

        $this->assertEquals(
            $restClient,
            $this->object->create('baseUrl', 'devId', 'apiId', 'certId', 'authToken')
        );
    }
}
