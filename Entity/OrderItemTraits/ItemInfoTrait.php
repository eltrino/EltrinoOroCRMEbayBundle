<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 4/7/14
 * Time: 9:34 PM
 */

namespace Eltrino\EbayBundle\Entity\OrderItemTraits;
use Eltrino\EbayBundle\Model\OrderItem\ItemInfo;

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