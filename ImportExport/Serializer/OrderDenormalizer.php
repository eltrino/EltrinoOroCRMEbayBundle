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

namespace Eltrino\OroCrmEbayBundle\ImportExport\Serializer;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

use Eltrino\OroCrmEbayBundle\Model\Order\OrderFactory;
use Eltrino\OroCrmEbayBundle\Provider\EbayOrderConnector;

class OrderDenormalizer implements SerializerAwareInterface, DenormalizerInterface
{
    /**
     * @var SerializerInterface|NormalizerInterface|DenormalizerInterface
     */
    private $serializer;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Model\Order\OrderFactory
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
