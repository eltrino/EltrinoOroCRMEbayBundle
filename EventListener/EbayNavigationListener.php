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
namespace Eltrino\OroCrmEbayBundle\EventListener;

use Doctrine\ORM\EntityManager;

use Knp\Menu\ItemInterface;

use Oro\Bundle\IntegrationBundle\Entity\Channel;
use Oro\Bundle\NavigationBundle\Event\ConfigureMenuEvent;
use Eltrino\OroCrmEbayBundle\Provider\EbayChannelType;

/**
 * Class EbayNavigationListener.
 * @package Eltrino\OroCrmEbayBundle\EventListener
 */
class EbayNavigationListener
{
    const ORDER_MENU_ITEM = 'ebay_order';
    const BUYER_MENU_ITEM = 'ebay_buyer';

    protected static $map = [
        'order'    => [
            'parent'       => 'sales_tab',
            'prefix'       => self::ORDER_MENU_ITEM,
            'label'        => 'Ebay Orders',
            'route'        => 'eltrino_ebay_order_index',
            'extras' => [
                'routes'   => '/^eltrino_ebay_order_(index|view)$/',
                'position' => 50
            ]
        ],
        'buyer' => [
            'parent'       => 'customers_tab',
            'prefix'       => self::BUYER_MENU_ITEM,
            'label'        => 'Ebay Customers',
            'route'        => 'eltrino_ebay_buyer_index',
            'extras' => [
                'routes'   => '/^eltrino_ebay_buyer_(index|view)$/',
                'position' => 50
            ]
        ]
    ];

    /** @var EntityManager */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Adds dynamically menu entries depends on configured channels
     *
     * @param ConfigureMenuEvent $event
     */
    public function onNavigationConfigure(ConfigureMenuEvent $event)
    {
        $repository = $this->em->getRepository('OroIntegrationBundle:Channel');
        $channels   = $repository->getConfiguredChannelsForSync(EbayChannelType::TYPE);

        if ($channels) {
            $entries = [];
            /** @var Channel $channel */
            foreach ($channels as $channel) {
                if ($channel->getConnectors()) {
                    foreach ($channel->getConnectors() as $connector) {
                        if (!isset($entries[$connector])) {
                            $entries[$connector] = true;
                        }
                    }
                }
            }

            // walk trough prepared array
            foreach (array_keys($entries) as $key) {
                if (isset(self::$map[$key])) {
                    /** @var ItemInterface $reportsMenuItem */
                    $salesMenuItem = $event->getMenu()->getChild(self::$map[$key]['parent']);
                    $salesMenuItem->addChild(
                        self::$map[$key]['prefix'],
                        [
                            'label'  => self::$map[$key]['label'],
                            'route'  => self::$map[$key]['route'],
                            'extras' => array_merge(self::$map[$key]['extras'], ['skipBreadcrumbs' => true])
                        ]
                    );
                }
            }
        }
    }
}
