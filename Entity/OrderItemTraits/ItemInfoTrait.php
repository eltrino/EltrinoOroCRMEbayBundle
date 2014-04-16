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
namespace Eltrino\OroCrmEbayBundle\Entity\OrderItemTraits;
use Eltrino\OroCrmEbayBundle\Model\OrderItem\ItemInfo;

trait ItemInfoTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="item_id", type="string", length=60, nullable=false)
     */
    private $itemId;

    /**
     * @var string
     *
     * @ORM\Column(name="item_site", type="string", length=10)
     */
    private $itemSite;

    /**
     * @var string
     *
     * @ORM\Column(name="item_title", type="string", length=80)
     */
    private $itemTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="sku", type="string", length=60)
     */
    private $sku;

    /**
     * @var string
     *
     * @ORM\Column(name="condition_display_name", type="string", length=60)
     */
    private $conditionDisplayName;

    /**
     * @return ItemInfo
     */
    protected function initItemInfo()
    {
        return new ItemInfo($this->itemId, $this->itemSite, $this->itemTitle, $this->sku, $this->conditionDisplayName);
    }

    /**
     * @param ItemInfo $itemInfo
     */
    protected function initFromItemInfo(ItemInfo $itemInfo)
    {
        $this->itemId               = $itemInfo->getItemId();
        $this->itemSite             = $itemInfo->getItemSite();
        $this->itemTitle            = $itemInfo->getItemTitle();
        $this->sku                  = $itemInfo->getSku();
        $this->conditionDisplayName = $itemInfo->getConditionDisplayName();
    }
}
