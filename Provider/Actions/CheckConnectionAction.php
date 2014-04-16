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
namespace Eltrino\OroCrmEbayBundle\Provider\Actions;

use Eltrino\OroCrmEbayBundle\Ebay\Api\EbayRestClient;
use Eltrino\OroCrmEbayBundle\Ebay\Filters\Filter;

class CheckConnectionAction implements Action
{
    /**
     * @var \Eltrino\OroCrmEbayBundle\Ebay\EbayRestClient
     */
    private $ebayRestClient;

    /**
     * @param EbayRestClient $ebayRestClient
     */
    public function __construct(EbayRestClient $ebayRestClient)
    {
        $this->ebayRestClient = $ebayRestClient;
    }

    /**
     * @param Filter $filter
     * @return mixed
     */
    public function execute(Filter $filter)
    {
        return $this->ebayRestClient->getCheckRestClient()
            ->getTime($filter);
    }
}
