<?php

namespace Eltrino\EbayBundle\Tests\Provider;

use Eltrino\EbayBundle\Provider\EbayOrderConnector;

class EbayOrderConnectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Eltrino\EbayBundle\Provider\Actions\ActionFactory;
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

    /**
     * @var \Eltrino\EbayBundle\Ebay\EbayRestClientFactory
     */
    private $ebayRestClientFactory;

    /**
     * @var \Eltrino\EbayBundle\Ebay\Filters\FiltersFactory
     */
    private $filtersFactory;

    /**
     * @var \Eltrino\EbayBundle\Provider\EbayOrderConnector;
     */
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
        $this->assertEquals('Eltrino\\EbayBundle\\Entity\\Order', $this->ebayOrderConnector->getImportEntityFQCN());
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
        return $this->getMockBuilder('Eltrino\\EbayBundle\\Provider\\Actions\\ActionFactory')
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createContextRegistry()
    {
        return $this->getMockBuilder('Oro\\Bundle\\ImportExportBundle\\Context\\ContextRegistry')
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createContextMediator()
    {
        return $this->getMockBuilder('Oro\\Bundle\\IntegrationBundle\\Provider\\ConnectorContextMediator')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createEbayRestClientFactory()
    {
        return $this->getMockBuilder('Eltrino\\EbayBundle\\Ebay\\EbayRestClientFactory')
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createFiltersFactory()
    {
        return $this->getMockBuilder('Eltrino\\EbayBundle\\Ebay\\Filters\\FiltersFactory')
            ->getMock();
    }
}
