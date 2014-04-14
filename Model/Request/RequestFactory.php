<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 3/13/14
 * Time: 7:08 PM
 */

namespace Eltrino\OroCrmEbayBundle\Model\Request;

use Eltrino\OroCrmEbayBundle\Model\Request\Request;

class RequestFactory
{

    /**
     * Create Request
     *
     * @param $headers
     * @param $body
     * @return Request
     */
    public function create($headers, $body)
    {
        return new Request($headers, $body);
    }
}
