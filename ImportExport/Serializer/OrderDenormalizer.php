<?php

namespace ELtrino\EbayBundle\ImportExport\Serializer;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

use Eltrino\EbayBundle\Model\Order\OrderFactory;
use Eltrino\EbayBundle\Provider\EbayOrderConnector;

class OrderDenormalizer implements SerializerAwareInterface, DenormalizerInterface
{
    /**
     * @var SerializerInterface|NormalizerInterface|DenormalizerInterface
     */
    private $serializer;

    /**
     * @var \Eltrino\EbayBundle\Model\Order\OrderFactory
     */
    private $orderFactory;

    /**
     * @var  ChannelRepository
     */
    private $channelRepository;

    public function __construct(EntityManager $em, OrderFactory $orderFactory)
    {
        $this->channelRepository = $em->getRepository('OroIntegrationBundle:Channel');
        $this->orderFactory = $orderFactory;
    }

    /**
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param mixed $data
     * @param string $type
     * @param null $format
     * @return bool
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return is_object($data) && ($type == EbayOrderConnector::ORDER_TYPE);
    }

    /**
     * @param mixed $data
     * @param string $class
     * @param null $format
     * @param array $context
     * @return Order|object
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        $channel = $this->getChannelFromContext($context);

        /** @var Order $order */

        $order = $this->orderFactory->createOrder($data);
        $order->setChannel($channel);

        return $order;
    }

    /**
     * @param array $context
     *
     * @return \Oro\Bundle\IntegrationBundle\Entity\Channel
     * @throws \LogicException
     */
    protected function getChannelFromContext(array $context)
    {
        if (!isset($context['channel'])) {
            throw new \LogicException('Context should contain reference to channel');
        }

        return $this->channelRepository->getOrLoadById($context['channel']);
    }
}
