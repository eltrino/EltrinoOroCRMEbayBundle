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
use Eltrino\OroCrmEbayBundle\Provider\Iterator\EbayDataIterator;
use Eltrino\OroCrmEbayBundle\Provider\Iterator\Order\InitialModeLoader;
use Eltrino\OroCrmEbayBundle\Provider\Iterator\Order\UpdateModeLoader;
use Oro\Bundle\ImportExportBundle\Reader\IteratorBasedReader;
use Oro\Bundle\IntegrationBundle\Provider\ConnectorInterface;
use Oro\Bundle\IntegrationBundle\Provider\ConnectorContextMediator;
use Oro\Bundle\ImportExportBundle\Context\ContextInterface;
use Oro\Bundle\ImportExportBundle\Context\ContextRegistry;
use Eltrino\OroCrmEbayBundle\Ebay\Filters\FiltersFactory;
use Eltrino\OroCrmEbayBundle\Provider\Iterator\OrderIterator;
use OroCRM\Bundle\MagentoBundle\Provider\Iterator\UpdatedLoaderInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Oro\Bundle\IntegrationBundle\Entity\Status;

class EbayOrderConnector extends IteratorBasedReader implements ConnectorInterface
{
    const ORDER_TYPE = 'Eltrino\OroCrmEbayBundle\Entity\Order';

    /**
     * @var ContextRegistry
     */
    protected $contextRegistry;

    /**
     * @var ConnectorContextMediator
     */
    protected $contextMediator;

    /**
     * @var EbayRestClientFactory
     */
    private $ebayRestClientFactory;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Ebay\Filters\FiltersFactory
     */
    private $filtersFactory;

    /**
     * @param ContextRegistry $contextRegistry
     * @param ConnectorContextMediator $contextMediator
     * @param EbayRestClientFactory $ebayRestClientFactory
     * @param FiltersFactory $filtersFactory
     */
    public function __construct(ContextRegistry $contextRegistry, ConnectorContextMediator $contextMediator,
                                EbayRestClientFactory $ebayRestClientFactory, FiltersFactory $filtersFactory)
    {
        $this->contextRegistry = $contextRegistry;
        $this->contextMediator = $contextMediator;
        $this->ebayRestClientFactory = $ebayRestClientFactory;
        $this->filtersFactory = $filtersFactory;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return 'Order Connector';
    }

    /**
     * @param ContextInterface $context
     */
    protected function initializeFromContext(ContextInterface $context)
    {
        $channel = $this->contextMediator->getChannel($context);
        $settings = $channel->getTransport()->getSettingsBag();

        $ebayRestClient = $this->initializeEbayRestClient($settings);

        /** @var Status $status */
        $status = $channel
            ->getStatusesForConnector($this->getType(), Status::STATUS_COMPLETED)
            ->first();

        $loader = null;
        if (false !== $status) { // update_mode
            $loader = new UpdateModeLoader($ebayRestClient, $this->filtersFactory, $status->getDate());
        } else { // initial_mode
            $loader = new InitialModeLoader($ebayRestClient, $this->filtersFactory, $settings->get('start_sync_date'));
        }
        $orderIterator = new EbayDataIterator($loader);
        $this->setSourceIterator($orderIterator);
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

    /**
     * Returns entity name that will be used for matching "import processor"
     *
     * @return string
     */
    public function getImportEntityFQCN()
    {
        return self::ORDER_TYPE;
    }

    /**
     * Returns job name for import
     *
     * @return string
     */
    public function getImportJobName()
    {
        return 'ebay_order_import';
    }

    /**
     * Returns type name, the same as registered in service tag
     *
     * @return string
     */
    public function getType()
    {
        return 'order';
    }
}
