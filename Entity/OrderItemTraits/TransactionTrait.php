<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 4/7/14
 * Time: 9:34 PM
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
