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
