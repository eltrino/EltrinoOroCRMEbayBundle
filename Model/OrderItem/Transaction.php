<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 4/7/14
 * Time: 9:29 PM
 */

namespace Eltrino\OroCrmEbayBundle\Model\OrderItem;

class Transaction
{
    /**
     * @var string
     */
    private $transactionId;

    /**
     * @var string
     */
    private $transactionPrice;

    /**
     * @var string
     */
    private $currencyId;

    /**
     * @var string
     */
    private $totalTaxAmount;

    public function __construct($transactionId, $transactionPrice, $currencyId, $totalTaxAmount)
    {
        $this->transactionId         = $transactionId;
        $this->transactionPrice      = $transactionPrice;
        $this->totalTaxAmount        = $totalTaxAmount;
        $this->currencyId            = $currencyId;
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @return string
     */
    public function getTransactionPrice()
    {
        return $this->transactionPrice;
    }

    /**
     * @return string
     */
    public function getTotalTaxAmount()
    {
        return $this->totalTaxAmount;
    }

    /**
     * @return string
     */
    public function getCurrencyId()
    {
        return $this->currencyId;
    }
}
