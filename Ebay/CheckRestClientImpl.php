<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 4/9/14
 * Time: 4:09 PM
 */

namespace Eltrino\OroCrmEbayBundle\Ebay;

use Eltrino\OroCrmEbayBundle\Ebay\Api\CheckRestClient;
use Eltrino\OroCrmEbayBundle\Ebay\Api\AuthorizationHandler;
use Eltrino\OroCrmEbayBundle\Ebay\Filters\ComponentFilter;
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

    public function getTime(ComponentFilter $componentFilter)
    {
        $body = $componentFilter->process($this->getBody());

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
