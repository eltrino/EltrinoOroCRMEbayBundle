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
namespace Eltrino\OroCrmEbayBundle\Provider\Iterator\Order;

use Eltrino\OroCrmEbayBundle\Ebay\Client\Request;
use Eltrino\OroCrmEbayBundle\Ebay\Filters\Filter;
use Eltrino\OroCrmEbayBundle\Ebay\RestClient;
use Eltrino\OroCrmEbayBundle\Provider\Iterator\Loader;
use Guzzle\Http\Message\Response;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class OrderLoader implements LoggerAwareInterface, Loader
{
    use LoggerAwareTrait;

    /** @var Filter */
    protected $firstFilter;

    /**
     * @param RestClient $client
     * @param Filter     $firstFilter
     * @param string     $nameSpace
     */
    public function __construct(RestClient $client, Filter $firstFilter)
    {
        $this->client = $client;
        $this->firstFilter = $firstFilter;
    }

    /**
     * @return array
     */
    public function load()
    {
        $request                = $this->getRequest();
        $response               = $this->client->sendRequest($request);
        $elements               = $this->processResponse($response);
        return $elements;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRequest()
    {
        return new Request(RestClient::GET_ORDERS, $this->firstFilter);
    }

    /**
     * {@inheritdoc}
     */
    protected function processResponse(Response $response)
    {
        $result = $response->xml();
        $ordersXml = $result->OrderArray;
        return $this->extractOrders($ordersXml);
    }

    /**
     * @param \SimpleXMLElement $ordersXml
     * @return array
     */
    protected function extractOrders(\SimpleXMLElement $ordersXml)
    {
        $orders = [];
        if ($ordersXml->count() && $ordersXml->children()->count()) {
            foreach ($ordersXml->children() as $order) {
                $EbayOrderId = (string)$order->EbayOrderId;
                if ($EbayOrderId) {
                    $orders[] = $order;
                }
            }
        }
        return $orders;
    }
}
