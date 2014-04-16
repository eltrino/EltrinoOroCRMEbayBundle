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
