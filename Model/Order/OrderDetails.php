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
namespace Eltrino\OroCrmEbayBundle\Model\Order;
use Eltrino\OroCrmEbayBundle\Model\Order\Payment;
use Eltrino\OroCrmEbayBundle\Model\Order\Shipping;

class OrderDetails
{
    /**
     * @var string
     */
    private $orderStatus;

    /**
     * @var string
     */
    private $subtotal;

    /**
     * @var string
     */
    private $total;

    /**
     * @var string
     */
    private $sellerEmail;

    /**
     * @var Payment
     */
    private $payment;

    /**
     * @var Shipping
     */
    private $shipping;

    function __construct($orderStatus, $subtotal, $total, $sellerEmail, Payment $payment, Shipping $shipping)
    {
        $this->orderStatus = $orderStatus;
        $this->subtotal = $subtotal;
        $this->total = $total;
        $this->sellerEmail = $sellerEmail;
        $this->payment = $payment;
        $this->shipping = $shipping;
    }

    /**
     * @return string
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * @return string
     */
    public function getSellerEmail()
    {
        return $this->sellerEmail;
    }

    /**
     * @return string
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * @return string
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @return Shipping
     */
    public function getShipping()
    {
        return $this->shipping;
    }

}
