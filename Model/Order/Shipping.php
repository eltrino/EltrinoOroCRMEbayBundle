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
namespace Eltrino\OroCrmEbayBundle\Model\Order;


class Shipping
{
    /**
     * @var string
     */
    private $salesTaxPercent;

    /**
     * @var string
     */
    private $salesTaxAmount;

    /**
     * @var string
     */
    private $shippingService;

    /**
     * @var string
     */
    private $shippingServiceCost;

    public function __construct($salesTaxPercent, $salesTaxAmount, $shippingService, $shippingServiceCost)
    {
        $this->salesTaxPercent     = $salesTaxPercent;
        $this->salesTaxAmount      = $salesTaxAmount;
        $this->shippingService     = $shippingService;
        $this->shippingServiceCost = $shippingServiceCost;
    }

    /**
     * @return float
     */
    public function getSalesTaxAmount()
    {
        return $this->salesTaxAmount;
    }

    /**
     * @return string
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
