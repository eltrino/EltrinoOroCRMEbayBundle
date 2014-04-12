<?php

namespace Eltrino\EbayBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eltrino\EbayBundle\Entity\OrderTraits\OrderTrait;
use Symfony\Component\HttpFoundation\ParameterBag;
use Oro\Bundle\IntegrationBundle\Entity\Transport;
use Oro\Bundle\IntegrationBundle\Model\IntegrationEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Eltrino\EbayBundle\Entity\OrderTraits\OrderDetailsTrait;

use Eltrino\EbayBundle\Model\Order\OrderDetails;


/**
 * Class Order
 *
 * @package Eltrino\EbayBundle\Entity
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
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order",cascade={"all"})
     */
    private $items;

    /**
     * @var Customer
     *
     * @ORM\OneToOne(targetEntity="Customer", mappedBy="order",cascade={"all"})
     */
    private $customer;

    /**
     * @var OrderDetails
     */
    private $orderDetails;

    /**
     * @param $ebayOrderId
     * @param $buyerUserId
     * @param $sellerUserId
     * @param OrderDetails $orderDetails
     * @param $items
     * @param Customer $customer
     * @param null $createdTime
     */
    public function __construct($ebayOrderId, $buyerUserId, $sellerUserId, OrderDetails $orderDetails,
                                $items, Customer $customer, $createdAt = null)
    {
        $this->ebayOrderId         = $ebayOrderId;
        $this->buyerUserId         = $buyerUserId;
        $this->sellerUserId        = $sellerUserId;
        $this->orderDetails        = $orderDetails;
        $this->items               = $items;
        $this->customer            = $customer;
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

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param $customer
     * @return $this
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
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
            $payment  = $this->initPayment($this->amountPaid, $this->currencyId, $this->paymentMethods);
            $shipping = $this->initShipping($this->salesTaxPercent, $this->salesTaxAmount, $this->shippingService,
                $this->shippingServiceCost);

            $this->orderDetails = new OrderDetails($this->orderStatus, $this->subtotal, $this->total,
                $this->sellerEmail, $payment, $shipping);
        }
    }
}
