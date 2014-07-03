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

namespace Eltrino\OroCrmEbayBundle\Controller;

use Eltrino\OroCrmEbayBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\IntegrationBundle\Entity\Channel;

/**
 * @Route("/buyer")
 */
class BuyerController extends Controller
{
    /**
     * @Route("/{id}", name="eltrino_ebay_buyer_index")
     * @Template
     */
    public function indexAction(Channel $channel)
    {
        return ['channelId' => $channel->getId()];
    }

    /**
     * @Route("/view/{id}", name="eltrino_ebay_buyer_view", requirements={"id"="\d+"}))
     * @Template
     */
    public function viewAction(User $user)
    {
        return ['entity' => $user];
    }

    /**
     * @Route("/info/{id}", name="eltrino_ebay_buyer_widget_info", requirements={"id"="\d+"}))
     * @Template
     */
    public function infoAction(User $user)
    {
        return ['entity' => $user];
    }

    /**
     * @Route("/widget/grid/{id}", name="eltrino_ebay_buyer_addresses_widget", requirements={"id"="\d+"}))
     * @Template
     */
    public function addressesAction(User $user)
    {
        return ['entity' => $user];
    }
}
