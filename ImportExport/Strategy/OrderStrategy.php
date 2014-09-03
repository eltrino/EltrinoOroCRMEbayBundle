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
namespace Eltrino\OroCrmEbayBundle\ImportExport\Strategy;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityRepository;

use Eltrino\OroCrmEbayBundle\Entity\Order;

use Eltrino\OroCrmEbayBundle\Provider\EbayBuyerConnector;
use Oro\Bundle\ImportExportBundle\Context\ContextAwareInterface;
use Oro\Bundle\ImportExportBundle\Context\ContextInterface;
use Oro\Bundle\ImportExportBundle\Strategy\Import\ImportStrategyHelper;
use Oro\Bundle\ImportExportBundle\Strategy\StrategyInterface;


class OrderStrategy implements StrategyInterface, ContextAwareInterface
{
    /** @var ImportStrategyHelper */
    private $strategyHelper;

    /** @var ContextInterface */
    private $context;

    /**
     * @param ImportStrategyHelper $strategyHelper
     */
    public function __construct(ImportStrategyHelper $strategyHelper)
    {
        $this->strategyHelper = $strategyHelper;
    }

    /**
     * @param mixed $importedOrder
     * @return mixed|null|object
     * @throws \Oro\Bundle\ImportExportBundle\Exception\InvalidArgumentException
     * @throws \Oro\Bundle\ImportExportBundle\Exception\LogicException
     */
    public function process($importedOrder)
    {
        $criteria = ['ebayOrderId' => $importedOrder->getEbayOrderId(), 'channel' => $importedOrder->getChannel()];
        $order    = $this->getEntityByCriteria($criteria, $importedOrder);

        if ($order) {
            $this->strategyHelper->importEntity($order, $importedOrder, array('buyer'));
        } else {
            $order = $importedOrder;
        }

        $this->processItems($order, $importedOrder);
        $this->processBuyer($order);

        // check errors, update context increments
        return $this->validateAndUpdateContext($order);
    }

    /**
     * @param Order $entityToUpdate
     * @param Order $entityToImport
     */
    private function processItems(Order $entityToUpdate, Order $entityToImport)
    {
        foreach ($entityToImport->getItems() as $item) {
            if (!$item->getOrder()) {
                $item->setOrder($entityToUpdate);
            }
        }
    }

    public function processBuyer(Order $order)
    {
        $buyer = $order->getBuyer();
        if ($buyer) {
            $criteria = ['EIASToken' => $buyer->getEIASToken(), 'channel' => $order->getChannel()];
            $buyer = $this->getEntityByCriteria($criteria, EbayBuyerConnector::USER_ENTITY);
        }
        $order->setBuyer($buyer);
    }

    /**
     * @param ContextInterface $context
     */
    public function setImportExportContext(ContextInterface $context)
    {
        $this->context = $context;
    }

    /**
     * @param array         $criteria
     * @param object|string $entity object to get class from or class name
     *
     * @return object
     */
    private function getEntityByCriteria(array $criteria, $entity)
    {
        if (is_object($entity)) {
            $entityClass = ClassUtils::getClass($entity);
        } else {
            $entityClass = $entity;
        }

        return $this->getEntityRepository($entityClass)->findOneBy($criteria);
    }

    /**
     * @param $entityName
     *
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEntityManager($entityName)
    {
        return $this->strategyHelper->getEntityManager($entityName);
    }

    /**
     * @param string $entityName
     *
     * @return EntityRepository
     */
    private function getEntityRepository($entityName)
    {
        return $this->strategyHelper->getEntityManager($entityName)->getRepository($entityName);
    }

    /**
     * @param object $entity
     *
     * @return null|object
     */
    private function validateAndUpdateContext($entity)
    {
        // validate entity
        $validationErrors = $this->strategyHelper->validateEntity($entity);
        if ($validationErrors) {
            $this->context->incrementErrorEntriesCount();
            $this->strategyHelper->addValidationErrors($validationErrors, $this->context);

            return null;
        }

        // increment context counter
        if ($entity->getId()) {
            $this->context->incrementUpdateCount();
        } else {
            $this->context->incrementAddCount();
        }

        return $entity;
    }

}
