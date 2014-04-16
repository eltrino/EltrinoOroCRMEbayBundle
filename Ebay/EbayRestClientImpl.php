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
namespace Eltrino\OroCrmEbayBundle\Ebay;

use Eltrino\OroCrmEbayBundle\Ebay\Api\AuthorizationHandler;
use Eltrino\OroCrmEbayBundle\Ebay\Api\EbayRestClient;
use Eltrino\OroCrmEbayBundle\Ebay\Api\OrderRestClient;
use Eltrino\OroCrmEbayBundle\Ebay\Api\CheckRestClient;
use Guzzle\Http\ClientInterface;

class EbayRestClientImpl implements EbayRestClient
{
    /**
     * @var OrderRestClient
     */
    private $orderRestClient;

    /**
     * @var CheckRestClient
     */
    private $checkRestClient;

    public function __construct(ClientInterface $client, AuthorizationHandler $authHandler)
    {
        $this->orderRestClient  = new OrderRestClientImpl($client, $authHandler);
        $this->checkRestClient  = new CheckRestClientImpl($client, $authHandler);
    }

    /**
     * Retrieves Ebay Order Client
     * @return OrderRestClient
     */
    function getOrderRestClient()
    {
        return $this->orderRestClient;
    }

    /**
     * @return CheckRestClient
     */
    function getCheckRestClient()
    {
        return $this->checkRestClient;
    }
}
