<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 4/4/14
 * Time: 9:57 AM
 */

namespace Eltrino\EbayBundle\Entity\OrderTraits;
use Eltrino\EbayBundle\Model\Order\Payment;

trait PaymentTrait
{
    /**
     * @var float
     *
     * @ORM\Column(name="amount_paid", type="float")
     */
    private $amountPaid;

    /**
     * @var string
     *
     * @ORM\Column(name="currency_id", type="string", length=32, nullable=false)
     */
    private $currencyId;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_methods", type="string", length=60, nullable=false)
     */
    private $paymentMethods;

    /**
     * @return Payment
     */
    protected function initPayment()
    {
        return new Payment($this->amountPaid, $this->currencyId, $this->paymentMethods);
    }

    /**
     * @param Payment $payment
     */
    protected function initFromPayment(Payment $payment)
    {
        $this->amountPaid     = $payment->getAmountPaid();
        $this->currencyId     = $payment->getCurrencyId();
        $this->paymentMethods = $payment->getPaymentMethods();
    }
}