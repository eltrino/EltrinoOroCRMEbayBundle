<?php

namespace Eltrino\OroCrmEbayBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\IntegrationBundle\Entity\Channel;
use Eltrino\OroCrmEbayBundle\Entity\Order;


/**
 * @Route("/order")
 */
class OrderController extends Controller
{
    /**
     * @Route("/{id}", name="eltrino_ebay_order_index")
     * @Template
     */
    public function indexAction(Channel $channel)
    {
        return ['channelId' => $channel->getId()];
    }

    /**
     * @Route("/view/{id}", name="eltrino_ebay_order_view", requirements={"id"="\d+"}))
     * @Template
     */
    public function viewAction(Order $order)
    {
        return ['entity' => $order];
    }

    /**
     * @Route("/info/{id}", name="eltrino_ebay_order_widget_info", requirements={"id"="\d+"}))
     * @Template
     */
    public function infoAction(Order $order)
    {
        return ['entity' => $order];
    }

    /**
     * @Route("/widget/grid/{id}", name="eltrino_ebay_order_widget_items", requirements={"id"="\d+"}))
     * @Template
     */
    public function itemsAction(Order $order)
    {
        return ['entity' => $order];
    }
}
