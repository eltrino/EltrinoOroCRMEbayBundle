<?php
/**
 * Created by PhpStorm.
 * User: Ruslan Voitenko
 * Date: 4/10/14
 * Time: 5:08 PM
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
