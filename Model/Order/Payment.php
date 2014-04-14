<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 4/4/14
 * Time: 9:57 AM
 */

namespace Eltrino\OroCrmEbayBundle\Model\Order;

/**
 * Class File, Value Object
 * @package Eltrino\OroCrmEbayBundle\Model
 */
class Payment
{
    /**
     * @var string
     */
    private $amountPaid;

    /**
     * @var string
     */
    private $currencyId;

    /**
     * @var string
     */
    private $paymentMethods;

    public function __construct($amountPaid, $currencyId, $paymentMethods)
    {
        $this->amountPaid     = $amountPaid;
        $this->currencyId     = $currencyId;
        $this->paymentMethods = $paymentMethods;
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


}
