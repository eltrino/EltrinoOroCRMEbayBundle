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
