<?php
/**
 * Created by PhpStorm.
 * User: Ruslan Voitenko
 * Date: 4/8/14
 * Time: 1:58 AM
 */

namespace Eltrino\EbayBundle\EventListener;

use Doctrine\ORM\EntityManager;

use Knp\Menu\ItemInterface;

use Symfony\Component\Routing\RouterInterface;

use Oro\Bundle\IntegrationBundle\Entity\Channel;
use Oro\Bundle\NavigationBundle\Event\ConfigureMenuEvent;
use Eltrino\EbayBundle\Provider\EbayChannelType;

/**
 * Class EbayNavigationListener.
 * @package Eltrino\EbayBundle\EventListener
 */
class EbayNavigationListener
{
    const ORDER_MENU_ITEM = 'ebay_order';

    protected static $map = [
        'order'    => [
            'parent'       => 'sales_tab',
            'prefix'       => self::ORDER_MENU_ITEM,
            'label'        => 'Ebay',
            'route'        => 'eltrino_ebay_order_index',
            'extra_routes' => '/^eltrino_ebay_order_(index|view)$/'
        ]
    ];

    /** @var EntityManager */
    protected $em;

    /** @var RouterInterface */
    protected $router;

    public function __construct(EntityManager $em, RouterInterface $router)
    {
        $this->em     = $em;
        $this->router = $router;
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
                            $entries[$connector] = [];
                        }
                        $entries[$connector][] = ['id' => $channel->getId(), 'label' => $channel->getName()];
                    }
                }
            }

            // walk trough prepared array
            foreach ($entries as $key => $items) {
                if (isset(self::$map[$key])) {
                    /** @var ItemInterface $reportsMenuItem */
                    $salesMenuItem = $event->getMenu()->getChild(self::$map[$key]['parent'])->getChild('magento_order');
                    if ($salesMenuItem) {
                        foreach ($items as $entry) {
                            $salesMenuItem->addChild(
                                implode([self::$map[$key]['prefix'], $entry['id']]),
                                [
                                    'route' => self::$map[$key]['route'],
                                    'routeParameters' => ['id' => $entry['id']],
                                    'label' => $entry['label'],
                                    'check_access' => false,
                                    'extras' => ['routes' => self::$map[$key]['extra_routes']]
                                ]
                            );
                        }
                    }
                }
            }
        }
    }
}
