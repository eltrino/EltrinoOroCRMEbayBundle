<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 3/13/14
 * Time: 7:08 PM
 */

namespace Eltrino\EbayBundle\Provider\Actions;

use Eltrino\EbayBundle\Provider\Actions\CheckConnectionAction;
use Eltrino\EbayBundle\Provider\Actions\OrderAction;
use Eltrino\EbayBundle\Ebay\Api\EbayRestClient;

class ActionFactory
{
    /**
     * @return OrderAction
     */
    public function createOrderAction(EbayRestClient $ebayRestClient)
    {
        return new OrderAction($ebayRestClient);
    }

    /**
     * @return CheckConnectionAction
     */
    public function createCheckConnectionAction(EbayRestClient $ebayRestClient)
    {
        return new CheckConnectionAction($ebayRestClient);
    }
}
