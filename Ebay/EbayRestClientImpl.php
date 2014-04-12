<?php
/**
 * Created by PhpStorm.
 * User: Ruslan Voitenko
 * Date: 3/20/14
 * Time: 4:11 PM
 */

namespace Eltrino\EbayBundle\Ebay;

use Eltrino\EbayBundle\Ebay\Api\AuthorizationHandler;
use Eltrino\EbayBundle\Ebay\Api\EbayRestClient;
use Eltrino\EbayBundle\Ebay\Api\OrderRestClient;
use Eltrino\EbayBundle\Ebay\Api\CheckRestClient;
use Guzzle\Http\ClientInterface;

class EbayRestClientImpl implements EbayRestClient
{
    /**
     * @var OrderRestClient
     */
    private $orderRestClient;

    /**
     * @var CheckRestClient
     */
    private $checkRestClient;

    public function __construct(ClientInterface $client, AuthorizationHandler $authHandler)
    {
        $this->orderRestClient  = new OrderRestClientImpl($client, $authHandler);
        $this->checkRestClient  = new CheckRestClientImpl($client, $authHandler);
    }

    /**
     * Retrieves Ebay Order Client
     * @return OrderRestClient
     */
    function getOrderRestClient()
    {
        return $this->orderRestClient;
    }

    /**
     * @return CheckRestClient
     */
    function getCheckRestClient()
    {
        return $this->checkRestClient;
    }
}
