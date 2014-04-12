<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 4/7/14
 * Time: 8:54 PM
 */

namespace Eltrino\EbayBundle\Entity\OrderTraits;

trait OrderTrait
{
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

    /**
     * @return string
     */
    public function getAmountPaid()
    {
        return $this->amountPaid;
    }

    /**
     * @return string
     */
    public function getCurrencyId()
    {
        return $this->currencyId;
    }

    /**
     * @return string
     */
    public function getPaymentMethods()
    {
        return $this->paymentMethods;
    }

    /**
     * @return float
     */
    public function getSalesTaxAmount()
    {
        return $this->salesTaxAmount;
    }

    /**
     * @return float
     */
    public function getSalesTaxPercent()
    {
        return $this->salesTaxPercent;
    }

    /**
     * @return string
     */
    public function getShippingService()
    {
        return $this->shippingService;
    }

    /**
     * @return float
     */
    public function getShippingServiceCost()
    {
        return $this->shippingServiceCost;
    }
} 