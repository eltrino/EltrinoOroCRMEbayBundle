<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 3/13/14
 * Time: 6:22 PM
 */

namespace Eltrino\EbayBundle\Provider\Actions;
use Eltrino\EbayBundle\Ebay\Filters\Filter;

interface Action
{
    /**
     * @return mixed
     */
    public function execute(Filter $filter);
}
