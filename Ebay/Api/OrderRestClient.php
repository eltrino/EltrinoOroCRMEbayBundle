<?php
/**
 * Created by PhpStorm.
 * User: Ruslan Voitenko
 * Date: 3/20/14
 * Time: 4:09 PM
 */

namespace Eltrino\EbayBundle\Ebay\Api;
use Eltrino\EbayBundle\Ebay\Filters\Filter;

interface OrderRestClient
{
    /**
     * @param Filter $filter
     * @return mixed
     */
    function getOrders(Filter $filter);
}
