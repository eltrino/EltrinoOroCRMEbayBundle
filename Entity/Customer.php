<?php

namespace Eltrino\OroCrmEbayBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eltrino\OroCrmEbayBundle\Entity\CustomerTraits\CustomerAddressTrait;
use Eltrino\OroCrmEbayBundle\Entity\CustomerTraits\CustomerTrait;
use Eltrino\OroCrmEbayBundle\Model\Customer\CustomerAddress;

/**
 * Class Customer
 *
 * @package Eltrino\OroCrmEbayBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="eltrino_ebay_customer")
 */
class Customer
{
    use CustomerAddressTrait;
    use CustomerTrait;

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
     * @ORM\Column(name="buyer_user_id", type="string", length=60, nullable=true)
     */
    private $buyerUserId;

    /**
     * @var string
     *
     * @ORM\Column(name="buyer_name", type="string", length=128, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=128, nullable=true)
     */
    private $phone;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Model\Customer\CustomerAddress
     */
    private $customerAddress;

    /**
     * @param $buyerUserId
     * @param $name
     * @param $phone
     * @param CustomerAddress $customerAddress
     */
    public function __construct($buyerUserId, $name, $phone, CustomerAddress $customerAddress)
    {
        $this->buyerUserId = $buyerUserId;
        $this->name = $name;
        $this->phone = $phone;
        $this->customerAddress = $customerAddress;

        $this->initFromCustomerAddress($customerAddress);
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
    public function getBuyerUserId()
    {
        return $this->buyerUserId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return \Eltrino\OroCrmEbayBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
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
