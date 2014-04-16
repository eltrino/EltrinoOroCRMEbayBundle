<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 4/15/14
 * Time: 6:32 PM
 */

namespace Eltrino\OroCrmEbayBundle\Tests\ImportExport\Serializer;

use Eltrino\OroCrmEbayBundle\ImportExport\Serializer\OrderDenormalizer;
use Eltrino\OroCrmEbayBundle\Provider\EbayOrderConnector;

class OrderDenormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Eltrino\OroCrmEbayBundle\Model\Order\OrderFactory
     */
    private $orderFactory;

    /**
     * @var \Oro\Bundle\IntegrationBundle\Entity\Repository\ChannelRepository
     */
    private $channelRepository;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Oro\Bundle\IntegrationBundle\Entity\Channel
     */
    private $channel;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Entity\Order
     */
    private $order;

    public function setUp()
    {
        $this->orderFactory = $this->getMockBuilder('Eltrino\OroCrmEbayBundle\Model\Order\OrderFactory')
            ->getMock();

        $this->channelRepository = $this->getMockBuilder('Oro\Bundle\IntegrationBundle\Entity\Repository\ChannelRepository')
            ->disableOriginalConstructor()->getMock();

        $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()->getMock();

        $this->channel = $this->getMockBuilder('Oro\Bundle\IntegrationBundle\Entity\Channel')
            ->disableOriginalConstructor()->getMock();

        $this->order = $this->getMockBuilder('Eltrino\OroCrmEbayBundle\Entity\Order')
            ->disableOriginalConstructor()->getMock();
    }

    public function testDenormalize()
    {
        $data = new \SimpleXMLElement('<Order><OrderId>1</OrderId></Order>');
        $class = '';
        $context = array(
            'channel' => '100'
        );

        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with('OroIntegrationBundle:Channel')
            ->will($this->returnValue($this->channelRepository));

        $this->channelRepository->expects($this->once())
            ->method('getOrLoadById')
            ->with($context['channel'])
            ->will($this->returnValue($this->channel));

        $this->orderFactory
            ->expects($this->once())
            ->method('createOrder')
            ->with($this->equalTo($data))
            ->will($this->returnValue($this->order));

        $this->order
            ->expects($this->once())
            ->method('setChannel')
            ->with($this->equalTo($this->channel))
            ->will($this->returnValue($this->order));

        $orderDenormilizer = new OrderDenormalizer($this->em, $this->orderFactory);
        $orderDenormilizer->denormalize($data, $class, null, $context);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Context should contain reference to channel
     */
    public function testDenormalizeWithoutChannel()
    {
        $data = new \SimpleXMLElement('<Order><OrderId>1</OrderId></Order>');
        $class = '';
        $context = array();

        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with('OroIntegrationBundle:Channel')
            ->will($this->returnValue($this->channelRepository));

        $orderDenormilizer = new OrderDenormalizer($this->em, $this->orderFactory);
        $orderDenormilizer->denormalize($data, $class, null, $context);
    }

    public function testSupportsDenormalization()
    {
        $testObject = new \SimpleXMLElement('<Order><OrderId>1</OrderId></Order>');

        $orderDenormilizer = new OrderDenormalizer($this->em, $this->orderFactory);
        $this->assertFalse($orderDenormilizer->supportsDenormalization(array(), 'TEST_TYPE'));
        $this->assertFalse($orderDenormilizer->supportsDenormalization('string', 'TEST_TYPE'));
        $this->assertTrue($orderDenormilizer->supportsDenormalization($testObject, EbayOrderConnector::ORDER_TYPE));
    }

}