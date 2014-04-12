<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 3/31/14
 * Time: 1:20 PM
 */

namespace Eltrino\EbayBundle\Ebay\Filters;

interface Filter
{
    /**
     * @param string $body
     * @return string
     */
    function process($body);
}
