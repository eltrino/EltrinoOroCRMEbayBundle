<?php
/**
 * Created by PhpStorm.
 * User: Ruslan Voitenko
 * Date: 4/10/14
 * Time: 5:04 PM
 */

namespace Eltrino\EbayBundle\Provider\Iterator\Order;

use Eltrino\EbayBundle\Ebay\Filters\CompositeFilter;
use Eltrino\EbayBundle\Ebay\Filters\FiltersFactory;
use Eltrino\EbayBundle\Provider\Actions\Action;

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
