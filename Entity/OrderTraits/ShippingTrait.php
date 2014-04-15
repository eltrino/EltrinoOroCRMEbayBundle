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
use Eltrino\OroCrmEbayBundle\Model\Order\Shipping;

trait ShippingTrait
{
    /**
     * @var float
     *
     * @ORM\Column(name="sales_tax_percent", type="float")
     */
    private $salesTaxPercent;

    /**
     * @var float
     *
     * @ORM\Column(name="sales_tax_amount", type="float")
     */
    private $salesTaxAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="shipping_service", type="string", length=32, nullable=false)
     */
    private $shippingService;

    /**
     * @var float
     *
     * @ORM\Column(name="shipping_service_cost", type="float")
     */
    private $shippingServiceCost;

    /**
     * @return Shipping
     */
    protected function initShipping()
    {
        return new Shipping($this->salesTaxPercent, $this->salesTaxAmount, $this->shippingService,
            $this->shippingServiceCost);
    }

    /**
     * @param Shipping $shipping
     */
    protected function initFromShipping(Shipping $shipping)
    {
        $this->salesTaxPercent     = $shipping->getSalesTaxPercent();
        $this->salesTaxAmount      = $shipping->getSalesTaxAmount();
        $this->shippingService     = $shipping->getShippingService();
        $this->shippingServiceCost = $shipping->getShippingServiceCost();
    }
}
