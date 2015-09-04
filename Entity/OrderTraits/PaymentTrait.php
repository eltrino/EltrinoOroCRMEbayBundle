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
namespace Eltrino\OroCrmEbayBundle\Entity\OrderTraits;
use Eltrino\OroCrmEbayBundle\Model\Order\Payment;

trait PaymentTrait
{
    /**
     * @var float
     *
     * @ORM\Column(name="amount_paid", type="float")
     */
    private $amountPaid;

    /**
     * @var float
     *
     * @ORM\Column(name="currency_id", type="string", length=32, nullable=false)
     */
    private $currencyId;

    /**
     * @var float
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
