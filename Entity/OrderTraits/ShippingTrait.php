<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 4/4/14
 * Time: 12:46 PM
 */

namespace Eltrino\EbayBundle\Entity\OrderTraits;
use Eltrino\EbayBundle\Model\Order\Shipping;

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