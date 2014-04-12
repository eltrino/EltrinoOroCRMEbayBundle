<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 4/4/14
 * Time: 1:32 PM
 */

namespace Eltrino\EbayBundle\Entity\OrderTraits;

use Eltrino\EbayBundle\Entity\OrderTraits\ShippingTrait;
use Eltrino\EbayBundle\Entity\OrderTraits\PaymentTrait;
use Eltrino\EbayBundle\Model\Order\OrderDetails;

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