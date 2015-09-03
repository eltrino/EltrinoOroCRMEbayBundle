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
namespace Eltrino\OroCrmEbayBundle\Provider;

use Eltrino\OroCrmEbayBundle\Provider\Transport\EbayRestTransport;
use Eltrino\OroCrmEbayBundle\Ebay\Client\RestClientFactory;
use Eltrino\OroCrmEbayBundle\Ebay\Filters\FiltersFactory;
use Oro\Bundle\IntegrationBundle\Provider\AbstractConnector;
use Oro\Bundle\IntegrationBundle\Entity\Repository;
use Oro\Bundle\IntegrationBundle\Provider\ConnectorContextMediator;
use Oro\Bundle\ImportExportBundle\Context\ContextRegistry;
use Oro\Bundle\IntegrationBundle\Entity\Status;

class EbayOrderConnector extends AbstractConnector
{
    const ORDER_TYPE = 'Eltrino\OroCrmEbayBundle\Entity\Order';
    const TYPE       = 'order';

    /** @var EbayRestTransport */
    protected $transport;

    /**
     * @param ContextRegistry $contextRegistry
     * @param ConnectorContextMediator $contextMediator
     * @param RestClientFactory $RestClientFactory
     * @param FiltersFactory $filtersFactory
     */
    public function __construct(ContextRegistry $contextRegistry, ConnectorContextMediator $contextMediator,
                                RestClientFactory $RestClientFactory, FiltersFactory $filtersFactory)
    {
        $this->contextRegistry = $contextRegistry;
        $this->contextMediator = $contextMediator;
        $this->ebayRestClientFactory = $RestClientFactory;
        $this->filtersFactory = $filtersFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'Order Connector';
    }

    /**
     * {@inheritdoc}
     */
    public function getImportEntityFQCN()
    {
        return self::ORDER_TYPE;
    }

    /**
     * {@inheritdoc}
     */
    public function getImportJobName()
    {
        return 'ebay_order_import';
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return static::TYPE;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConnectorSource()
    {
        $settings = $this->channel->getTransport()->getSettingsBag();

        /** @var Status $status */
        $status = $this->channel
            ->getStatusesForConnector($this->getType(), Status::STATUS_COMPLETED)
            ->first();

        if (false !== $status) {
            return $this->transport->getModOrders($status->getDate());
        } else {
            return $this->transport->getInitialOrders($settings->get('start_sync_date'));
        }
    }
}
