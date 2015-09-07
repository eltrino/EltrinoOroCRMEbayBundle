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
use Eltrino\OroCrmEbayBundle\Entity\OrderTraits\OrderTrait;
use Symfony\Component\HttpFoundation\ParameterBag;
use Oro\Bundle\IntegrationBundle\Entity\Transport;
use Oro\Bundle\IntegrationBundle\Model\IntegrationEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Eltrino\OroCrmEbayBundle\Entity\OrderTraits\OrderDetailsTrait;

use Eltrino\OroCrmEbayBundle\Model\Order\OrderDetails;

/**
 * Class Order
 *
 * @package Eltrino\OroCrmEbayBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="eltrino_ebay_order")
 */
class Order
{
    use IntegrationEntityTrait;
    use OrderDetailsTrait;
    use OrderTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ebay_order_id", type="string", length=60, nullable=false)
     */
    private $ebayOrderId;

    /**
     * @var string
     *
     * @ORM\Column(name="buyer_user_id", type="string", length=60, nullable=false)
     */
    private $buyerUserId;

    /**
     * @var string
     *
     * @ORM\Column(name="seller_user_id", type="string", length=60, nullable=false)
     */
    private $sellerUserId;

    /**
     * @var \DateTime $createdAt
     *
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order",cascade={"all"}, orphanRemoval=true)
     */
    private $items;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", cascade={"all"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $buyer;

    /**
     * @var OrderDetails
     */
    private $orderDetails;

    public $channelId;

    /**
     * @param $ebayOrderId
     * @param $buyerUserId
     * @param $sellerUserId
     * @param OrderDetails $orderDetails
     * @param $items
     * @param User $buyer
     * @param null $createdAt
     */
    public function __construct($ebayOrderId, $buyerUserId, $sellerUserId, OrderDetails $orderDetails,
                                $items, User $buyer, $createdAt = null)
    {
        $this->ebayOrderId         = $ebayOrderId;
        $this->buyerUserId         = $buyerUserId;
        $this->sellerUserId        = $sellerUserId;
        $this->orderDetails        = $orderDetails;
        $this->items               = $items;
        $this->buyer               = $buyer;
        $this->createdAt           = is_null($createdAt) ? new \DateTime('now') : $createdAt;

        $this->updatedAt = clone $this->createdAt;

        $this->initFromShipping($orderDetails->getShipping());
        $this->initFromPayment($orderDetails->getPayment());
        $this->initFromOrderDetails($orderDetails);
    }

    /**
     * @return string
     */
    public function getBuyerUserId()
    {
        return $this->buyerUserId;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return string
     */
    public function getChannelId()
    {
        return $this->channelId;
    }

    /**
     * @return string
     */
    public function getEbayOrderId()
    {
        return $this->ebayOrderId;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSellerUserId()
    {
        return $this->sellerUserId;
    }

    /**
     * @param  $items
     *
     * @return $this
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return User
     */
    public function getBuyer()
    {
        return $this->buyer;
    }

    /**
     * @param User|null $buyer
     */
    public function setBuyer($buyer)
    {
        if (!($buyer instanceof User || null == $buyer)) {
            throw new \InvalidArgumentException('$buyer parameter has wrong type. $buyer should be instance of User or null.');
        }
        $this->buyer = $buyer;
    }

    /**
     * @return OrderDetails
     */
    public function getOrderDetails()
    {
        $this->initOrderDetails();
        return $this->orderDetails;
    }


    private function initOrderDetails()
    {
        if (is_null($this->orderDetails)) {
            $payment  = $this->initPayment();
            $shipping = $this->initShipping();

            $this->orderDetails = new OrderDetails($this->orderStatus, $this->subtotal, $this->total,
                $this->sellerEmail, $payment, $shipping);
        }
    }
}
