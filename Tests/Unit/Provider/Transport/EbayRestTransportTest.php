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
use Eltrino\OroCrmEbayBundle\Ebay\Client\RestClientFactory;
use Eltrino\OroCrmEbayBundle\Ebay\Filters\CreateTimeRangeFilter;
use Eltrino\OroCrmEbayBundle\Ebay\Filters\FiltersFactory;
use Eltrino\OroCrmEbayBundle\Ebay\Filters\ModTimeRangeFilter;
use Eltrino\OroCrmEbayBundle\Ebay\RestClient;
use Eltrino\OroCrmEbayBundle\Provider\Transport\EbayRestTransport;
use Eltrino\OroCrmEbayBundle\Entity\EbayRestTransport as TransportEntity;

class EbayRestTransportTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|RestClientFactory
     */
    protected $clientFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|FiltersFactory
     */
    protected $filtersFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|RestClient
     */
    protected $client;

    /**
     * @var EbayRestTransport
     */
    protected $object;

    protected function setUp()
    {
        $this->client = $this
            ->getMockBuilder('Eltrino\OroCrmEbayBundle\Ebay\RestClient')
            ->disableOriginalConstructor()
            ->getMock();

        $this->clientFactory = $this
            ->getMockBuilder('Eltrino\OroCrmEbayBundle\Ebay\Client\RestClientFactory')
            ->getMock();

        $this->clientFactory
            ->expects($this->any())
            ->method('create')
            ->willReturn($this->client);

        $this->filtersFactory = $this
            ->getMockBuilder('Eltrino\OroCrmEbayBundle\Ebay\Filters\FiltersFactory')
            ->getMock();

        $this->object = new EbayRestTransport($this->clientFactory, $this->filtersFactory);
        $date   = new \DateTime('now', new \DateTimeZone('UTC'));
        $entity = new TransportEntity();

        $entity
            ->setKeyId('keyId')
            ->setSecret('secret')
            ->setMerchantId('merchantId')
            ->setMarketplaceId('marketplaceId')
            ->setSyncStartDate($date)
            ->setWsdlUrl('wsdlUrl');
        $this->object->init($entity);
    }

    public function testGetOrders()
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $from = clone $now;
        $from->sub(new \DateInterval('PT3M'));
        $iteratorClass = 'Eltrino\OroCrmEbayBundle\Provider\Iterator\EbayDataIterator';

        $this->filtersFactory
            ->expects($this->at(0))
            ->method('createCreateTimeRangeFilter')
            ->willReturn(new CreateTimeRangeFilter($from, $now));

        $this->filtersFactory
            ->expects($this->at(1))
            ->method('createModTimeRangeFilter')
            ->willReturn(new ModTimeRangeFilter($from, $now));

        $this->assertInstanceOf(
            $iteratorClass,
            $this->object->getInitialOrders($from)
        );

        $this->assertInstanceOf(
            $iteratorClass,
            $this->object->getModOrders($from)
        );
    }

    public function testGetStatus()
    {
        $xml = new \SimpleXMLElement(file_get_contents(__DIR__ . '/../../Fixtures/GetServiceStatus.xml'));
        $response = $this
            ->getMockBuilder('Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $response
            ->expects($this->once())
            ->method('xml')
            ->willReturn($xml);

        $this->client
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn($response);

        $this->assertTrue($this->object->getStatus());
    }

    public function testGetLabel()
    {
        $this->assertEquals(
            'eltrino.ebay.transport.rest.label',
            $this->object->getLabel()
        );
    }

    public function testGetSettingsEntityFQCN ()
    {
        $this->assertEquals(
            'Eltrino\OroCrmEbayBundle\Entity\EbayRestTransport',
            $this->object->getSettingsEntityFQCN()
        );
    }

    public function testGetSettingsFormType()
    {
        $this->assertEquals(
            'eltrino_ebay_rest_transport_setting_form_type',
            $this->object->getSettingsFormType()
        );
    }
}
