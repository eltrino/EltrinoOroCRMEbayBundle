<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 4/9/14
 * Time: 4:07 PM
 */

namespace Eltrino\OroCrmEbayBundle\Ebay\Api;
use Eltrino\OroCrmEbayBundle\Ebay\Filters\Filter;

interface CheckRestClient
{
    /**
     * @param ComponentFilter $componentFilter
     * @return mixed
     */
    function getTime(Filter $filter);
}
