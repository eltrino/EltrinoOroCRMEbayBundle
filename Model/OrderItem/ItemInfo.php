<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 4/7/14
 * Time: 9:29 PM
 */

namespace Eltrino\OroCrmEbayBundle\Model\OrderItem;

class ItemInfo
{
    /**
     * @var string
     */
    private $itemId;

    /**
     * @var string
     */
    private $itemSite;

    /**
     * @var string
     */
    private $itemTitle;

    /**
     * @var string
     */
    private $sku;

    /**
     * @var string
     */
    private $conditionDisplayName;

    public function __construct($itemId, $itemSite, $itemTitle, $sku, $conditionDisplayName)
    {
        $this->itemId               = $itemId;
        $this->itemSite             = $itemSite;
        $this->itemTitle            = $itemTitle;
        $this->sku                  = $sku;
        $this->conditionDisplayName = $conditionDisplayName;
    }

    /**
     * @return mixed
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * @return string
     */
    public function getItemSite()
    {
        return $this->itemSite;
    }

    /**
     * @return string
     */
    public function getItemTitle()
    {
        return $this->itemTitle;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @return string
     */
    public function getConditionDisplayName()
    {
        return $this->conditionDisplayName;
    }
}
