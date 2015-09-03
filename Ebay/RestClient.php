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
use Eltrino\OroCrmEbayBundle\Ebay\Client\Request;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\Response;

class RestClient
{
    const GET_SERVICE_STATUS             = 'GeteBayOfficialTime';
    const GET_ORDERS                     = 'GetOrders';
    const STATUS_SUCCESS                 = 'Success';
    const COMPATIBILITY_LEVEL            = '859';
    const SITE_ID                        = '0';

    /**
     * @var array
     *
     * 'max_requests_quota' => The maximum size that the request quota can reach.
     * 'restore_rate' => The rate at which your request quota increases over time, up to the maximum request quota.
     */
    protected static $throttlingParams = [
        self::GET_SERVICE_STATUS             => ['max_requests_quota' => 2, 'restore_rate' => 300],
        self::GET_ORDERS                     => ['max_requests_quota' => 6, 'restore_rate' => 60]
    ];

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var AuthorizationHandler
     */
    protected $authHandler;

    /**
     * @var array
     * Store counters for api requests.
     * The number of requests that you can submit at one time without throttling
     * for each action
     */
    protected $requestsCounters  = [
        self::GET_SERVICE_STATUS => 0,
        self::GET_ORDERS         => 0
    ];

    /**
     * @var array
     * Time in seconds which left from other actions recovery rate and
     * can be used to decrease request counter for current action
     */
    protected $requestsExtraTime = [
        self::GET_SERVICE_STATUS => 0,
        self::GET_ORDERS         => 0
    ];

    /**
     * @var array
     */
    protected $restoreRateRequests = [
        self::GET_SERVICE_STATUS   => 0,
        self::GET_ORDERS           => 0
    ];

    /**
     * @param ClientInterface      $client
     * @param AuthorizationHandler $authHandler
     */
    public function __construct(ClientInterface $client, AuthorizationHandler $authHandler)
    {
        $this->client      = $client;
        $this->authHandler = $authHandler;
    }
    /**
     * @param Request $request
     * @return Response
     */
    public function sendRequest(Request $request)
    {
        $action = $request->getAction();
        if (!isset(static::$throttlingParams[$action])) {
            throw new \InvalidArgumentException('Unknown action ' . $action);
        }
        $requestParameters = $this->createRequestParameters($action, $request);
        $body = $request->processFiltersParameters($this->getBody($action));
        $response = $this->client->post(null, $requestParameters, $body)->send();

        return $response;
    }

    /**
     * @param string  $action
     * @param Request $request
     * @return array
     */
    protected function createRequestParameters($action, Request $request)
    {
        $requestParameters = array_merge(
            [
                'X-EBAY-API-COMPATIBILITY-LEVEL' => self::COMPATIBILITY_LEVEL,
                'X-EBAY-API-DEV-NAME'            => $this->authHandler->getDevId(),
                'X-EBAY-API-APP-NAME'            => $this->authHandler->getAppId(),
                'X-EBAY-API-CERT-NAME'           => $this->authHandler->getCertId(),
                'X-EBAY-API-SITEID'              => self::SITE_ID,
                'X-EBAY-API-CALL-NAME'           => $action
            ],
            $request->getParameters()
        );

        return $requestParameters;
    }

    /**
     * Retrieves request body
     * @param $action
     * @return string
     */
    protected function getBody($action)
    {
        switch($action) {
            case $action == self::GET_SERVICE_STATUS:
                return '<?xml version="1.0" encoding="utf-8"?>
                <GeteBayOfficialTimeRequest xmlns="urn:ebay:apis:eBLBaseComponents">
                    <RequesterCredentials>
                        <eBayAuthToken>' . $this->authHandler->getAuthToken() . '</eBayAuthToken>
                    </RequesterCredentials>
                </GeteBayOfficialTimeRequest>';

            case $action == self::GET_ORDERS:
                return '<?xml version="1.0" encoding="utf-8"?>
                <GetOrdersRequest xmlns="urn:ebay:apis:eBLBaseComponents">
                    <RequesterCredentials>
                    <eBayAuthToken>' . $this->authHandler->getAuthToken() . '</eBayAuthToken>
                    </RequesterCredentials>
                    <OrderRole>Seller</OrderRole>
                    <Pagination>
                        <EntriesPerPage>100</EntriesPerPage>
                        <PageNumber>1</PageNumber>
                    </Pagination>
                    <SortingOrder>Ascending</SortingOrder>
                </GetOrdersRequest>';
        }
    }
}
