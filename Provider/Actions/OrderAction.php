<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 3/13/14
 * Time: 6:29 PM
 */

namespace Eltrino\OroCrmEbayBundle\Provider\Actions;

use Eltrino\OroCrmEbayBundle\Ebay\Api\EbayRestClient;
use Eltrino\OroCrmEbayBundle\Ebay\Filters\Filter;

class OrderAction implements Action
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
        return $this->ebayRestClient->getOrderRestClient()
            ->getOrders($filter);
    }
}
