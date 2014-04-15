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
namespace Eltrino\OroCrmEbayBundle\Provider\Iterator\Order;

use Eltrino\OroCrmEbayBundle\Ebay\Filters\CompositeFilter;
use Eltrino\OroCrmEbayBundle\Ebay\Filters\FiltersFactory;
use Eltrino\OroCrmEbayBundle\Provider\Actions\Action;

class InitialModeLoader extends AbstractLoader
{
    public function load()
    {
        $elements = array();
        do {
            list($from, $to) = $this->prepareDateRange($this->startSyncDate, new \DateInterval('P90D'));
            $this->compositeFilter->reset();
            $this->compositeFilter->addFilter($this->createCreateTimeFilter($from, $to));
            $this->compositeFilter->addFilter($this->createPagerFilter($this->currentPage));
            $elements = $this->action->execute($this->compositeFilter);
            $this->currentPage++;
            if (empty($elements)) { // try to move to next date interval and load elements
                $this->startSyncDate = clone $to;
                $this->currentPage = self::PAGE_NUMBER_TO_START;
            }
        } while (empty($elements) && $this->startSyncDate < $this->now);

        return $elements;
    }
}
