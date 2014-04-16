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

use Eltrino\OroCrmEbayBundle\Entity\OrderTraits\ShippingTrait;
use Eltrino\OroCrmEbayBundle\Entity\OrderTraits\PaymentTrait;
use Eltrino\OroCrmEbayBundle\Model\Order\OrderDetails;

trait OrderDetailsTrait
{
    use PaymentTrait;
    use ShippingTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="order_status", type="string", length=60, nullable=false)
     */
    private $orderStatus;

    /**
     * @var float
     *
     * @ORM\Column(name="subtotal", type="float")
     */
    private $subtotal;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float")
     */
    private $total;

    /**
     * @var string
     *
     * @ORM\Column(name="seller_email", type="string", length=60, nullable=false)
     */
    private $sellerEmail;

    protected function initFromOrderDetails(OrderDetails $orderDetails)
    {
        $this->orderStatus = $orderDetails->getOrderStatus();
        $this->subtotal    = $orderDetails->getSubtotal();
        $this->total       = $orderDetails->getTotal();
        $this->sellerEmail = $orderDetails->getSellerEmail();
    }

}
