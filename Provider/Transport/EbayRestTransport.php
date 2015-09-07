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
namespace Eltrino\OroCrmEbayBundle\Provider\Transport;

use Eltrino\OroCrmEbayBundle\Ebay\Client\Request;
use Eltrino\OroCrmEbayBundle\Ebay\Client\RestClientFactory;
use Eltrino\OroCrmEbayBundle\Ebay\Filters\Filter;
use Eltrino\OroCrmEbayBundle\Provider\Iterator\EbayDataIterator;
use Eltrino\OroCrmEbayBundle\Provider\Iterator\Order\OrderLoader;
use Eltrino\OroCrmEbayBundle\Provider\Iterator\User\UsersLoader;
use Eltrino\OroCrmEbayBundle\Ebay\RestClient;
use Eltrino\OroCrmEbayBundle\Ebay\Filters\FiltersFactory;
use Guzzle\Http\Message\Response;
use Oro\Bundle\IntegrationBundle\Entity\Transport;
use Eltrino\OroCrmEbayBundle\Provider\Iterator\OrderRestIterator;
use Oro\Bundle\IntegrationBundle\Provider\TransportInterface;

/**
 * Ebay REST transport
 * used to fetch and pull data to/from Ebay instance
 * with sessionId param using REST requests
 *
 * @package Eltrino\OroCrmEbayBundle
 */
class EbayRestTransport implements TransportInterface
{
    /** @var RestClient */
    protected $ebayClient;

    /** @var FiltersFactory */
    protected $filtersFactory;

    /** @var RestClientFactory */
    protected $clientFactory;

    /**
     * @param RestClientFactory $clientFactory
     * @param FiltersFactory    $filtersFactory
     */
    public function __construct(RestClientFactory $clientFactory, FiltersFactory $filtersFactory)
    {
        $this->clientFactory  = $clientFactory;
        $this->filtersFactory = $filtersFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'eltrino.ebay.transport.rest.label';
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingsFormType()
    {
        return 'eltrino_ebay_rest_transport_setting_form_type';
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingsEntityFQCN()
    {
        return 'Eltrino\OroCrmEbayBundle\Entity\EbayRestTransport';
    }

    /**
     * {@inheritdoc}
     */
    public function init(Transport $transportEntity)
    {
        $settings           = $transportEntity->getSettingsBag();
        $baseUrl            = $settings->get('wsdl_url');
        $this->ebayClient   = $this->clientFactory->create(
            $baseUrl,
            $settings->get('dev_id'),
            $settings->get('app_id'),
            $settings->get('cert_id'),
            $settings->get('auth_token')
        );
    }

    /**
     * @return bool
     */
    public function getStatus()
    {
        $response = $this->ebayClient->sendRequest(new Request(RestClient::GET_SERVICE_STATUS));
        return $this->getStatusFromResponse($response);
    }
    /**
     * @param \DateTime $from
     * @return EbayDataIterator
     */
    public function getModOrders(\DateTime $from)
    {
        $now    = $this->getNowDate();
        $from   = $this->validateFrom($from, $now);
        $filter = $this
            ->filtersFactory
            ->createModTimeRangeFilter($from, $now);
        return $this->getOrders($filter);
    }

    /**
     * @param \DateTime $from
     * @return EbayDataIterator
     */
    public function getInitialOrders(\DateTime $from)
    {
        $now    = $this->getNowDate();
        $from   = $this->validateFrom($from, $now);
        $filter = $this
            ->filtersFactory
            ->createCreateTimeRangeFilter($from, $now);
        return $this->getOrders($filter);
    }

    /**
     * @param Response $response
     * @return bool
     * @throws
     */
    protected function getStatusFromResponse(Response $response)
    {
        $xml  = $response->xml();
        return (string)$xml->Ack === RestClient::STATUS_SUCCESS;
    }

    /**
     * @param Filter $filter
     * @return EbayDataIterator
     */
    protected function getOrders(Filter $filter)
    {
        $loader = new OrderLoader($this->ebayClient, $filter);

        return new EbayDataIterator($loader);
    }

    /**
     * @param \DateTime $from
     * @return EbayDataIterator
     */
    public function getModUsers(\DateTime $from)
    {
        $now    = $this->getNowDate();
        $from   = $this->validateFrom($from, $now);
        $filter = $this
            ->filtersFactory
            ->createModTimeRangeFilter($from, $now);
        return $this->getUsers($filter);
    }

    /**
     * @param Filter $filter
     * @return EbayDataIterator
     */
    protected function getUsers(Filter $filter)
    {
        $orderLoader = new OrderLoader($this->ebayClient, $filter);
        $usersLoader = new UsersLoader($orderLoader);

        return new EbayDataIterator($usersLoader);
    }

    /**
     * @return \DateTime
     */
    protected function getNowDate()
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        /**
         * Ebay mws api requirement:
         * Must be no later than two minutes before the time that the request was submitted.
         */
        $now->sub(new \DateInterval('PT3M'));
        return $now;
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $now
     * @return \DateTime
     *
     * Check that from time not > now after now time was subbed.
     */
    protected function validateFrom(\DateTime $from, \DateTime $now)
    {
        if($from >= $now) {
            $from = clone $now;
            $from->sub(new \DateInterval('PT3M'));
        }
        return $from;
    }
}
