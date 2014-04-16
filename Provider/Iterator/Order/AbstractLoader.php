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
use Eltrino\OroCrmEbayBundle\Provider\Iterator\Loader;

abstract class AbstractLoader implements Loader
{
    const ENTRIES_PER_PAGE     = 25;
    const PAGE_NUMBER_TO_START = 1;

    /**
     * @var Action
     */
    protected $action;

    /**
     * @var FiltersFactory
     */
    protected $filtersFactory;

    /**
     * @var CompositeFilter
     */
    protected $compositeFilter;

    /**
     * @var \DateTime
     */
    protected $startSyncDate;

    protected $now;

    protected $currentPage = self::PAGE_NUMBER_TO_START;

    public function __construct(Action $action, FiltersFactory $filtersFactory, \DateTime $startSyncDate)
    {
        $this->action = $action;
        $this->filtersFactory = $filtersFactory;
        $this->startSyncDate = clone $startSyncDate;
        $this->now = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->compositeFilter = $this
            ->filtersFactory
            ->createCompositeFilter();
    }

    protected function createPagerFilter($currentPage)
    {
        return $this
            ->filtersFactory
            ->createPagerFilter(self::ENTRIES_PER_PAGE, $currentPage);
    }

    protected function createCreateTimeFilter(\DateTime $from, \DateTime $to)
    {
        return $this
            ->filtersFactory
            ->createCreateTimeRangeFilter($from, $to);
    }

    protected function modTimeFilter(\DateTime $from, \DateTime $to)
    {
        return $this
            ->filtersFactory
            ->createModTimeRangeFilter($from, $to);
    }

    protected function prepareDateRange(\DateTime $startSyncDate, \DateInterval $dateInterval)
    {
        $from = clone $startSyncDate;
        $to = clone $from;
        $to->add($dateInterval);
        return array($from, $to);
    }
}
