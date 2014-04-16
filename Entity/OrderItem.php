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

namespace Eltrino\OroCrmEbayBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eltrino\OroCrmEbayBundle\Entity\OrderItemTraits\OrderItemTrait;
use Eltrino\OroCrmEbayBundle\Entity\OrderItemTraits\TransactionTrait;
use Eltrino\OroCrmEbayBundle\Entity\OrderItemTraits\ItemInfoTrait;
use Eltrino\OroCrmEbayBundle\Model\OrderItem\ItemInfo;
use Eltrino\OroCrmEbayBundle\Model\OrderItem\Transaction;

/**
 * Class OrderItem
 *
 * @package Eltrino\OroCrmEbayBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="eltrino_ebay_order_items")
 */
class OrderItem
{
    use ItemInfoTrait;
    use TransactionTrait;
    use OrderItemTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="Order")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $order;

    /**
     * @var string
     *
     * @ORM\Column(name="buyer_email", type="string", length=60)
     */
    private $buyerEmail;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity_purchased", type="integer")
     */
    private $quantityPurchased;

    /**
     * @var string
     *
     * @ORM\Column(name="order_line_item_id", type="string", length=60)
     */
    private $orderLineItemId;

    /**
     * @var ItemInfo
     */
    private $itemInfo;

    /**
     * @var Transaction
     */
    private $transaction;

    public function __construct($buyerEmail, ItemInfo $itemInfo, $quantityPurchased, Transaction $transaction, $orderLineItemId)
    {
        $this->buyerEmail        = $buyerEmail;
        $this->itemInfo          = $itemInfo;
        $this->quantityPurchased = $quantityPurchased;
        $this->transaction       = $transaction;
        $this->orderLineItemId   = $orderLineItemId;

        $this->initFromItemInfo($itemInfo);
        $this->initFromTransaction($transaction);
    }

    /**
     * @return string
     */
    public function getBuyerEmail()
    {
        return $this->buyerEmail;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \Eltrino\OroCrmEbayBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return string
     */
    public function getOrderLineItemId()
    {
        return $this->orderLineItemId;
    }

    /**
     * @return int
     */
    public function getQuantityPurchased()
    {
        return $this->quantityPurchased;
    }

    /**
     * @param Order $order
     *
     * @return $this
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;

        return $this;
    }
}
