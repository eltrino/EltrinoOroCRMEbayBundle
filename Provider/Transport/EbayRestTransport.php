<?php

namespace Eltrino\EbayBundle\Provider\Transport;

use Oro\Bundle\IntegrationBundle\Entity\Transport;
use Eltrino\EbayBundle\Provider\Iterator\OrderRestIterator;
use Oro\Bundle\IntegrationBundle\Provider\TransportInterface;

/**
 * Ebay REST transport
 * used to fetch and pull data to/from Ebay instance
 * with sessionId param using REST requests
 *
 * @package Eltrino\EbayBundle
 */
class EbayRestTransport implements TransportInterface
{
    /**
     * @return string
     */
    public function getLabel()
    {
        return 'eltrino.ebay.transport.rest.label';
    }

    /**
     * @return string
     */
    public function getSettingsFormType()
    {
        return 'eltrino_ebay_rest_transport_setting_form_type';
    }

    /**
     * @return string
     */
    public function getSettingsEntityFQCN()
    {
        return 'Eltrino\\EbayBundle\\Entity\\EbayRestTransport';
    }



    /**
     * @param Transport $transportEntity
     */
    public function init(Transport $transportEntity)
    {

    }

    /**
     * @param string $action
     * @param array $params
     * @return array|mixed
     * @throws \Symfony\Component\DependencyInjection\Exception\RuntimeException
     */
    public function call($action, $params = [])
    {

    }
}
