<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 3/13/14
 * Time: 2:03 PM
 */

namespace Eltrino\OroCrmEbayBundle\Model\Client;

use Guzzle\Http\Client;

class ClientFactory
{
    /**
     * @param $baseUrl
     * @return Client
     */
    public function create($baseUrl)
    {
        return new Client($baseUrl);
    }
}
