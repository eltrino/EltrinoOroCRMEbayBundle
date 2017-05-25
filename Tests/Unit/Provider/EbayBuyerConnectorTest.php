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
namespace Eltrino\OroCrmEbayBundle\Tests\Unit\Provider;

use Eltrino\OroCrmEbayBundle\Provider\EbayBuyerConnector;
use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;
use PHPUnit\Framework\TestCase;

class EbayBuyerConnectorTest extends TestCase
{
    /**
     * @var \Akeneo\Bundle\BatchBundle\Entity\StepExecution
     * @Mock Akeneo\Bundle\BatchBundle\Entity\StepExecution
     */
    private $step;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Ebay\Filters\FiltersFactory
     * @Mock Eltrino\OroCrmEbayBundle\Ebay\Filters\FiltersFactory
     */
    private $filtersFactory;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Ebay\Client\RestClientFactory
     * @Mock Eltrino\OroCrmEbayBundle\Ebay\Client\RestClientFactory
     */
    private $ebayRestClient;

    /**
     * @var \Oro\Bundle\IntegrationBundle\Provider\ConnectorContextMediator
     * @Mock Oro\Bundle\IntegrationBundle\Provider\ConnectorContextMediator
     */
    private $contextMediator;

    /**
     * @var \Oro\Bundle\ImportExportBundle\Context\ContextRegistry
     * @Mock Oro\Bundle\ImportExportBundle\Context\ContextRegistry
     */
    private $contextRegistry;

    protected function setUp()
    {
        MockAnnotations::init($this);
    }

    public function testInterfaceGetters()
    {
        $connector = new EbayBuyerConnector(
            $this->contextRegistry, $this->contextMediator,
            $this->ebayRestClient, $this->filtersFactory
        );
        $this->assertEquals('Buyer Connector', $connector->getLabel());
        $this->assertEquals('Eltrino\OroCrmEbayBundle\Entity\User', $connector->getImportEntityFQCN());
        $this->assertEquals('ebay_buyer_import', $connector->getImportJobName());
        $this->assertEquals('buyer', $connector->getType());
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Reader must be configured with source
     */
    public function testReadWhenSourceIteratorIsNotConfigured()
    {
        $connector = new EbayBuyerConnector(
            $this->contextRegistry, $this->contextMediator,
            $this->ebayRestClient, $this->filtersFactory
        );

        $connector->read();
    }
}
