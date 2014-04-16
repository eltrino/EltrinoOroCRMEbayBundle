<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 4/15/14
 * Time: 6:32 PM
 */

namespace Eltrino\OroCrmEbayBundle\Tests\ImportExport\Srtategy;
use Eltrino\OroCrmEbayBundle\ImportExport\Strategy\OrderStrategy;

class OrderStrategyTest extends \PHPUnit_Framework_TestCase
{
    private $strategyHelper;

    private $order;

    private $em;

    private $repository;

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
    }

    public function testProcess()
    {
        /*$this->strategyHelper
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
            ->will($this->returnValue($this->order));

        $strategy = new OrderStrategy($this->strategyHelper);
        $strategy->process($this->order);*/
    }
}