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
/**
 * Created by PhpStorm.
 * User: Ruslan Voitenko
 * Date: 4/25/14
 * Time: 5:04 PM
 */

namespace Eltrino\OroCrmEbayBundle\Ebay;

use Eltrino\OroCrmEbayBundle\Ebay\Api\AuthorizationHandler;
use Guzzle\Http\ClientInterface;

abstract class AbstractRestClient
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var AuthorizationHandler
     */
    private $authHandler;

    /**
     * @param ClientInterface $client
     * @param AuthorizationHandler $authHandler
     */
    public function __construct(ClientInterface $client, AuthorizationHandler $authHandler)
    {
        $this->client = $client;
        $this->authHandler = $authHandler;
    }

    /**
     * @return AuthorizationHandler
     */
    protected function getAuthHandler()
    {
        return $this->authHandler;
    }

    abstract protected function getHeaders();

    protected function call($body)
    {
        return $this->client
            ->post(null, $this->getHeaders(), $body)
            ->send()
            ->xml();
    }
}
