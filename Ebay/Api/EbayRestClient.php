<?php
/**
 * Created by PhpStorm.
 * User: psw
 * Date: 3/20/14
 * Time: 4:09 PM
 */

namespace Eltrino\OroCrmEbayBundle\Ebay\Api;

interface EbayRestClient
{
    /**
     * Retrieves Ebay Order Client
     * @return OrderRestClient
     */
    function getOrderRestClient();

    /**
     * Retrieves Ebay Check Client
     * @return CheckRestClient
     */
    function getCheckRestClient();
}
