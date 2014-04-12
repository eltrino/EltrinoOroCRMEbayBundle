<?php

namespace Eltrino\EbayBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eltrino\EbayBundle\Entity\OrderItemTraits\OrderItemTrait;
use Eltrino\EbayBundle\Entity\OrderItemTraits\TransactionTrait;
use Eltrino\EbayBundle\Entity\OrderItemTraits\ItemInfoTrait;
use Eltrino\EbayBundle\Model\OrderItem\ItemInfo;
use Eltrino\EbayBundle\Model\OrderItem\Transaction;

/**
 * Class OrderItem
 *
 * @package Eltrino\EbayBundle\Entity
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
     * @return \Eltrino\EbayBundle\Entity\Order
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