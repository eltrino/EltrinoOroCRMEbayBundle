<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 3/13/14
 * Time: 6:22 PM
 */

namespace Eltrino\OroCrmEbayBundle\Provider\Actions;
use Eltrino\OroCrmEbayBundle\Ebay\Filters\Filter;

interface Action
{
    /**
     * @return mixed
     */
    public function execute(Filter $filter);
}
