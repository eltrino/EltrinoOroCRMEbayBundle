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

use Eltrino\OroCrmEbayBundle\Ebay\Api\CheckRestClient;
use Eltrino\OroCrmEbayBundle\Ebay\Api\AuthorizationHandler;
use Eltrino\OroCrmEbayBundle\Ebay\Filters\Filter;
use Guzzle\Http\ClientInterface;

class CheckRestClientImpl implements CheckRestClient
{
    /**
     * @var \Guzzle\Http\ClientInterface
     */
    private $client;

    /**
     * @var Api\AuthorizationHandler
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
     * @param Filter $filter
     * @return bool|mixed
     * @throws \Guzzle\Common\Exception\RuntimeException
     */
    public function getTime(Filter $filter)
    {
        $body = $filter->process($this->getBody());

        $response = $this->client
            ->post(null, $this->getHeaders(), $body)
            ->send()
            ->xml()
        ;

        return $this->parseResponse($response);
    }

    /**
     * Retrieves request headers
     * @return array
     */
    private function getHeaders()
    {
        return array
        (
            'X-EBAY-API-COMPATIBILITY-LEVEL' => 859,
            'X-EBAY-API-DEV-NAME' => $this->authHandler->getDevId(),
            'X-EBAY-API-APP-NAME' => $this->authHandler->getAppId(),
            'X-EBAY-API-CERT-NAME' => $this->authHandler->getCertId(),
            'X-EBAY-API-SITEID' => '0',
            'X-EBAY-API-CALL-NAME' => 'GeteBayOfficialTime'
        );
    }

    /**
     * Retrieves request body
     * @return string
     */
    private function getBody()
    {
        return '<?xml version="1.0" encoding="utf-8"?>
        <GeteBayOfficialTimeRequest xmlns="urn:ebay:apis:eBLBaseComponents">
            <RequesterCredentials>
                <eBayAuthToken>' . $this->authHandler->getAuthToken() . '</eBayAuthToken>
            </RequesterCredentials>
        </GeteBayOfficialTimeRequest>';
    }

    /**
     * @param \SimpleXMLElement $response
     * @return bool
     */
    private function parseResponse(\SimpleXMLElement $response)
    {
        $status = (string) $response->Ack;
        if ($status === 'Success') {
            return true;
        } else {
            return false;
        }
    }
}
