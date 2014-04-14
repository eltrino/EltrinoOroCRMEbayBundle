<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 4/4/14
 * Time: 12:40 PM
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
     * @return string
     */
    public function getShippingServiceCost()
    {
        return $this->shippingServiceCost;
    }


}
