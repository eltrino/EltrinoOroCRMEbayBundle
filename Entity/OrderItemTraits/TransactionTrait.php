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
namespace Eltrino\OroCrmEbayBundle\Entity\OrderItemTraits;
use Eltrino\OroCrmEbayBundle\Model\OrderItem\Transaction;

trait TransactionTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="transaction_id", type="string", length=60)
     */
    private $transactionId;

    /**
     * @var float
     *
     * @ORM\Column(name="transaction_price", type="float")
     */
    private $transactionPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="currency_id", type="string", length=32, nullable=false)
     */
    private $currencyId;

    /**
     * @var float
     *
     * @ORM\Column(name="total_tax_amount", type="float")
     */
    private $totalTaxAmount;

    /**
     * @return Transaction
     */
    protected function initTransaction()
    {
        return new Transaction($this->transactionId, $this->transactionPrice, $this->currencyId, $this->totalTaxAmount);
    }

    /**
     * @param Transaction $transaction
     */
    protected function initFromTransaction(Transaction $transaction)
    {
        $this->transactionId         = $transaction->getTransactionId();
        $this->transactionPrice      = $transaction->getTransactionPrice();
        $this->currencyId            = $transaction->getCurrencyId();
        $this->totalTaxAmount        = $transaction->getTotalTaxAmount();
    }
}
