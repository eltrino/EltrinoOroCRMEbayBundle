<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 3/31/14
 * Time: 2:45 PM
 */

namespace Eltrino\OroCrmEbayBundle\Ebay\Filters;

class FiltersFactory
{
    /**
     * @return CompositeFilter
     */
    public function createCompositeFilter()
    {
        return new CompositeFilter();
    }

    /**
     * @param integer $entriesPerPage
     * @param integer $page
     * @return PagerFilter
     */
    public function createPagerFilter($entriesPerPage, $page)
    {
        return new PagerFilter($entriesPerPage, $page);
    }

    public function createCreateTimeRangeFilter(\DateTime $from, \DateTime $to)
    {
        return new CreateTimeRangeFilter($from, $to);
    }

    public function createModTimeRangeFilter(\DateTime $from, \DateTime $to)
    {
        return new ModTimeRangeFilter($from, $to);
    }
}
