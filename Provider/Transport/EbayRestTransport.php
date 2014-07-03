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
        return 'Eltrino\OroCrmEbayBundle\Entity\EbayRestTransport';
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
