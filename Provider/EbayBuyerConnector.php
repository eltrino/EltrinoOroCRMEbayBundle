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
use Oro\Bundle\ImportExportBundle\Context\ContextInterface;
use Oro\Bundle\ImportExportBundle\Context\ContextRegistry;
use Oro\Bundle\ImportExportBundle\Reader\IteratorBasedReader;
use Oro\Bundle\IntegrationBundle\Entity\Status;
use Oro\Bundle\IntegrationBundle\Provider\ConnectorContextMediator;
use Oro\Bundle\IntegrationBundle\Provider\ConnectorInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Oro\Bundle\IntegrationBundle\Provider\AbstractConnector;

class EbayBuyerConnector extends AbstractConnector
{
    const USER_ENTITY = 'Eltrino\OroCrmEbayBundle\Entity\User';

    /**
     * @var ConnectorContextMediator
     */
    protected $contextMediator;

    /**
     * @var RestClientFactory
     */
    private $restClientFactory;

    /**
     * @var FiltersFactory
     */
    private $filtersFactory;

    /** @var EbayRestTransport */
    protected $transport;

    public function __construct(ContextRegistry $contextRegistry, ConnectorContextMediator $contextMediator,
                                RestClientFactory $restClientFactory, FiltersFactory $filtersFactory)
    {
        $this->contextRegistry       = $contextRegistry;
        $this->contextMediator       = $contextMediator;
        $this->restClientFactory     = $restClientFactory;
        $this->filtersFactory        = $filtersFactory;
    }

    /**
     * Returns label for UI
     *
     * @return string
     */
    public function getLabel()
    {
        return 'Buyer Connector';
    }

    /**
     * Returns entity name that will be used for matching "import processor"
     *
     * @return string
     */
    public function getImportEntityFQCN()
    {
        return self::USER_ENTITY;
    }

    /**
     * Returns job name for import
     *
     * @return string
     */
    public function getImportJobName()
    {
        return 'ebay_buyer_import';
    }

    /**
     * Returns type name, the same as registered in service tag
     *
     * @return string
     */
    public function getType()
    {
        return 'buyer';
    }

    /**
     * Placeholder function for "akeneo/batch-bundle"
     *
     * @return $this
     */
    public function initialize()
    {
        return $this;
    }

    /**
     * Placeholder function for "akeneo/batch-bundle"
     *
     * @return $this
     */
    public function flush()
    {
        return $this;
    }

    protected function getConnectorSource()
    {
        $settings = $this->channel->getTransport()->getSettingsBag();

        /** @var Status $status */
        $status = $this->channel
            ->getStatusesForConnector($this->getType(), Status::STATUS_COMPLETED)
            ->first();

        if (false !== $status) {
            return $this->transport->getModUsers($status->getDate());
        } else {
            return $this->transport->getModUsers($settings->get('start_sync_date'));
        }
    }
}
