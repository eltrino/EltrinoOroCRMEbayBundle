<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 4/4/14
 * Time: 1:23 PM
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
