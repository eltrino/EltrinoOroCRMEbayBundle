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

use Eltrino\OroCrmEbayBundle\Ebay\EbayRestClientFactory;
use Eltrino\OroCrmEbayBundle\Ebay\Filters\FiltersFactory;
use Eltrino\OroCrmEbayBundle\Provider\Iterator\EbayDataIterator;
use Eltrino\OroCrmEbayBundle\Provider\Iterator\Order\InitialModeLoader;
use Eltrino\OroCrmEbayBundle\Provider\Iterator\User\UsersLoader;
use Oro\Bundle\ImportExportBundle\Context\ContextInterface;
use Oro\Bundle\ImportExportBundle\Context\ContextRegistry;
use Oro\Bundle\ImportExportBundle\Reader\IteratorBasedReader;
use Oro\Bundle\IntegrationBundle\Entity\Status;
use Oro\Bundle\IntegrationBundle\Provider\ConnectorContextMediator;
use Oro\Bundle\IntegrationBundle\Provider\ConnectorInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

class EbayBuyerConnector extends IteratorBasedReader implements ConnectorInterface
{
    const USER_ENTITY = 'Eltrino\OroCrmEbayBundle\Entity\User';

    /**
     * @var ConnectorContextMediator
     */
    protected $contextMediator;

    /**
     * @var EbayRestClientFactory
     */
    private $ebayRestClientFactory;

    /**
     * @var FiltersFactory
     */
    private $filtersFactory;

    public function __construct(ContextRegistry $contextRegistry, ConnectorContextMediator $contextMediator,
                                EbayRestClientFactory $ebayRestClientFactory, FiltersFactory $filtersFactory)
    {
        parent::__construct($contextRegistry);
        $this->contextMediator       = $contextMediator;
        $this->ebayRestClientFactory = $ebayRestClientFactory;
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

    protected function initializeFromContext(ContextInterface $context)
    {
        $channel = $this->contextMediator->getChannel($context);
        $settings = $channel->getTransport()->getSettingsBag();

        $ebayRestClient = $this->initializeEbayRestClient($settings);

        /** @var Status $status */
        $status = $channel
            ->getStatusesForConnector($this->getType(), Status::STATUS_COMPLETED)
            ->first();

        $startSyncDate = $settings->get('start_sync_date');
        if (false !== $status) { // update_mode
            $startSyncDate = $status->getDate();
        }
        $ordersLoader = new InitialModeLoader($ebayRestClient, $this->filtersFactory, $startSyncDate);

        $loader = new UsersLoader($ordersLoader, $ebayRestClient);
        $iterator = new EbayDataIterator($loader);
        $this->setSourceIterator($iterator);
    }

    private function initializeEbayRestClient(ParameterBag $settings)
    {
        $ebayRestClient = $this->ebayRestClientFactory->create(
            $settings->get('wsdl_url'),
            $settings->get('dev_id'),
            $settings->get('app_id'),
            $settings->get('cert_id'),
            $settings->get('auth_token')
        );

        return $ebayRestClient;
    }
}
