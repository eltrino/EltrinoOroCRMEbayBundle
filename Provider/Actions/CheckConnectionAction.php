<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 3/13/14
 * Time: 6:29 PM
 */

namespace Eltrino\EbayBundle\Provider\Actions;

use Eltrino\EbayBundle\Ebay\Api\EbayRestClient;
use Eltrino\EbayBundle\Ebay\Filters\Filter;

class CheckConnectionAction implements Action
{
    /**
     * @var \Eltrino\EbayBundle\Ebay\EbayRestClient
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
        return $this->ebayRestClient->getCheckRestClient()
            ->getTime($filter);
    }
}
