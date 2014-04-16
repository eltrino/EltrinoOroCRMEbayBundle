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

use Eltrino\OroCrmEbayBundle\Ebay\Filters\FiltersFactory;
use Eltrino\OroCrmEbayBundle\Provider\Actions\Action;

class UpdateModeLoader extends AbstractLoader
{
    const UPDATE_SUB_PROCESS_CREATED = 'create';
    const UPDATE_SUB_PROCESS_UPDATE  = 'update';

    private $subProcess = self::UPDATE_SUB_PROCESS_CREATED;

    private $initialStartSyncDate;

    public function __construct(Action $action, FiltersFactory $filtersFactory, \DateTime $startSyncDate)
    {
        parent::__construct($action, $filtersFactory, $startSyncDate);
        $this->initialStartSyncDate = clone $this->startSyncDate;
    }

    public function load()
    {
        $elements = array();
        do {
            list($from, $to) = $this->prepareDateRange($this->startSyncDate, new \DateInterval('P90D'));
            $this->compositeFilter->reset();
            if ($this->subProcess == self::UPDATE_SUB_PROCESS_CREATED) {
                $timeFilter = $this->createCreateTimeFilter($from, $to);
            } else {
                list($from, $to) = $this->prepareDateRange($this->startSyncDate, new \DateInterval('P30D'));
                $timeFilter = $this->modTimeFilter($from, $to);
            }
            $this->compositeFilter->addFilter($timeFilter);
            $this->compositeFilter->addFilter($this->createPagerFilter($this->currentPage));
            $elements = $this->action->execute($this->compositeFilter);
            $this->currentPage++;
            if (empty($elements)) { // try to move to next date interval and load elements
                $this->startSyncDate = clone $to;
                $this->currentPage = self::PAGE_NUMBER_TO_START;
                if ($this->startSyncDate > $this->now && $this->subProcess == self::UPDATE_SUB_PROCESS_CREATED) {
                    $this->subProcess = self::UPDATE_SUB_PROCESS_UPDATE;
                    $this->startSyncDate = clone $this->initialStartSyncDate;
                }
            }
        } while (empty($elements) && $this->startSyncDate < $this->now);

        return $elements;
    }
}
