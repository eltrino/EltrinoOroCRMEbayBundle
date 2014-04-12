<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 4/9/14
 * Time: 4:07 PM
 */

namespace Eltrino\EbayBundle\Ebay\Api;
use Eltrino\EbayBundle\Ebay\Filters\ComponentFilter;

interface CheckRestClient
{
    /**
     * @param ComponentFilter $componentFilter
     * @return mixed
     */
    function getTime(ComponentFilter $componentFilter);
} 