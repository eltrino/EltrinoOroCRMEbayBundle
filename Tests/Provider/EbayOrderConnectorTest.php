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

namespace Eltrino\OroCrmEbayBundle\Tests\Provider;

use Eltrino\OroCrmEbayBundle\Provider\EbayOrderConnector;

class EbayOrderConnectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Eltrino\OroCrmEbayBundle\Provider\Actions\ActionFactory
     */
    private $actionFactory;

    /**
     * @var \Oro\Bundle\ImportExportBundle\Context\ContextRegistry
     */
    private $contextRegistry;

    /**
     * @var \Oro\Bundle\IntegrationBundle\Provider\ConnectorContextMediator;
     */
    private $contextMediator;

    private $ebayRestClientFactory;

    private $filtersFactory;

    private $ebayOrderConnector;

    public function setUp()
    {
        $this->actionFactory         = $this->createActionFactory();
        $this->contextRegistry       = $this->createContextRegistry();
        $this->contextMediator       = $this->createContextMediator();
        $this->ebayRestClientFactory = $this->createEbayRestClientFactory();
        $this->filtersFactory        = $this->createFiltersFactory();

        $this->ebayOrderConnector = new EbayOrderConnector($this->actionFactory, $this->contextRegistry,
            $this->contextMediator, $this->ebayRestClientFactory, $this->filtersFactory);
    }

    public function testGetLabel()
    {
        $this->assertEquals('Order connector', $this->ebayOrderConnector->getLabel());
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

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createActionFactory()
    {
        return $this->getMockBuilder('Eltrino\OroCrmEbayBundle\Provider\Actions\ActionFactory')
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createContextRegistry()
    {
        return $this->getMockBuilder('Oro\Bundle\ImportExportBundle\Context\ContextRegistry')
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createContextMediator()
    {
        return $this->getMockBuilder('Oro\Bundle\IntegrationBundle\Provider\ConnectorContextMediator')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createEbayRestClientFactory()
    {
        return $this->getMockBuilder('Eltrino\OroCrmEbayBundle\Ebay\EbayRestClientFactory')
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createFiltersFactory()
    {
        return $this->getMockBuilder('Eltrino\OroCrmEbayBundle\Ebay\Filters\FiltersFactory')
            ->getMock();
    }
}
