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
use Eltrino\OroCrmEbayBundle\Ebay\Api\OrderRestClient;
use Eltrino\OroCrmEbayBundle\Ebay\Filters\Filter;
use Guzzle\Http\ClientInterface;

class OrderRestClientImpl implements OrderRestClient
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
     * Retrieves Ebay orders
     * @param Filter $filter
     * @return array|mixed
     */
    public function getOrders(Filter $filter)
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
            'X-EBAY-API-CALL-NAME' => 'GetOrders'
        );
    }

    /**
     * Retrieves request body
     * @return string
     */
    private function getBody()
    {
        return '<?xml version="1.0" encoding="utf-8"?>
        <GetOrdersRequest xmlns="urn:ebay:apis:eBLBaseComponents">
            <RequesterCredentials>
            <eBayAuthToken>' . $this->authHandler->getAuthToken() . '</eBayAuthToken>
            </RequesterCredentials>
            <CreateTimeFrom>2014-01-01</CreateTimeFrom>
            <CreateTimeTo>2015-01-01</CreateTimeTo>
            <OrderRole>Seller</OrderRole>
            <Pagination>
                <EntriesPerPage>100</EntriesPerPage>
                <PageNumber>1</PageNumber>
            </Pagination>
            <SortingOrder>Ascending</SortingOrder>
        </GetOrdersRequest>';
    }

    /**
     * Parse response, retrievs orders array of SimpleXmlElement's
     * @param \SimpleXMLElement $response
     * @return array
     */
    private function parseResponse(\SimpleXMLElement $response)
    {
        $response->registerXPathNamespace('c', 'urn:ebay:apis:eBLBaseComponents');
        $orderArray = $response->xpath('c:OrderArray/c:Order');

        return $orderArray;
    }
}
