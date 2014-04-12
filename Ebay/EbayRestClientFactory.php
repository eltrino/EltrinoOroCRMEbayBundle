<?php
/**
 * Created by PhpStorm.
 * User: Ruslan Voitenko
 * Date: 3/20/14
 * Time: 4:10 PM
 */

namespace Eltrino\EbayBundle\Ebay;

use Guzzle\Http\Client;
use Guzzle\Http\ClientInterface;

class EbayRestClientFactory
{
    public function create($baseUrl, $devId, $apiId, $certId, $authToken)
    {
        $client = new Client($baseUrl);
        $authHandler = new DefaultAuthorizationHandler($devId, $apiId, $certId, $authToken);
        return new EbayRestClientImpl($client, $authHandler);
    }
}
