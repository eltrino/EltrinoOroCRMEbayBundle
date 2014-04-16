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
namespace Eltrino\OroCrmEbayBundle\Tests\ImportExport\Srtategy;
use Eltrino\OroCrmEbayBundle\ImportExport\Strategy\OrderStrategy;
use Eltrino\OroCrmEbayBundle\Model\Order\OrderFactory;

class OrderStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Oro\Bundle\ImportExportBundle\Strategy\Import\ImportStrategyHelper
     */
    private $strategyHelper;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Entity\Order
     */
    private $order;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $repository;

    /**
     * @var \Oro\Bundle\ImportExportBundle\Context\StepExecutionProxyContext
     */
    private $context;

    public function setUp()
    {
        $this->strategyHelper = $this->getMockBuilder('Oro\Bundle\ImportExportBundle\Strategy\Import\ImportStrategyHelper')
            ->disableOriginalConstructor()->getMock();

        $this->order = $this->getMockBuilder('Eltrino\OroCrmEbayBundle\Entity\Order')
            ->disableOriginalConstructor()->getMock();

        $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()->getMock();

        $this->repository = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectRepository')
            ->getMock();

        $this->context = $this->getMockBuilder('Oro\Bundle\ImportExportBundle\Context\StepExecutionProxyContext')
            ->disableOriginalConstructor()->getMock();
    }

    public function testProcess()
    {
        $order = $this->createOrder();

        $this->strategyHelper
            ->expects($this->once())
            ->method('getEntityManager')
            ->will($this->returnValue($this->em));

        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($this->repository));

        $this->repository
            ->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($order));

        $this->strategyHelper
            ->expects($this->once())
            ->method('importEntity')
            ->will($this->returnValue($this->em));

        $this->context
            ->expects($this->any())
            ->method('incrementAddCount');

        $this->context
            ->expects($this->any())
            ->method('incrementUpdateCount');

        $strategy = new OrderStrategy($this->strategyHelper);
        $strategy->setImportExportContext($this->context);
        $strategy->process($order);
    }

    /**
     * @return \Eltrino\OroCrmEbayBundle\Entity\Order
     */
    private function createOrder()
    {
        $fileName = __DIR__ . DIRECTORY_SEPARATOR . 'dictionaries' . DIRECTORY_SEPARATOR . "order.xml";
        $data = simplexml_load_file($fileName);

        $orderFactory = new OrderFactory();
        return $orderFactory->createOrder($data);
    }
}