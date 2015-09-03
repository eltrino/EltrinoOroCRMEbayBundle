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

use Eltrino\OroCrmEbayBundle\Provider\EbayOrderConnector;
use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

class EbayOrderConnectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Oro\Bundle\ImportExportBundle\Context\ContextRegistry
     * @Mock Oro\Bundle\ImportExportBundle\Context\ContextRegistry
     */
    private $contextRegistry;

    /**
     * @var \Oro\Bundle\IntegrationBundle\Provider\ConnectorContextMediator
     * @Mock Oro\Bundle\IntegrationBundle\Provider\ConnectorContextMediator
     */
    private $contextMediator;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Ebay\Client\RestClientFactory
     * @Mock Eltrino\OroCrmEbayBundle\Ebay\Client\RestClientFactory
     */
    private $ebayRestClientFactory;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Ebay\Filters\FiltersFactory
     * @Mock Eltrino\OroCrmEbayBundle\Ebay\Filters\FiltersFactory
     */
    private $filtersFactory;

    private $ebayOrderConnector;

    public function setUp()
    {
        MockAnnotations::init($this);
        $this->ebayOrderConnector = new EbayOrderConnector($this->contextRegistry,
            $this->contextMediator, $this->ebayRestClientFactory, $this->filtersFactory);
    }

    public function testGetLabel()
    {
        $this->assertEquals('Order Connector', $this->ebayOrderConnector->getLabel());
    }

    public function testGetImportJobName()
    {
        $this->assertEquals('ebay_order_import', $this->ebayOrderConnector->getImportJobName());
    }

    public function testGetImportEntityFQCN()
    {
        $this->assertEquals('Eltrino\OroCrmEbayBundle\Entity\Order', $this->ebayOrderConnector->getImportEntityFQCN());
    }

    public function testGetType()
    {
        $this->assertEquals('order', $this->ebayOrderConnector->getType());
    }
}
