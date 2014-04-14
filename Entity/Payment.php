<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 4/2/14
 * Time: 7:53 PM
 */

namespace Eltrino\OroCrmEbayBundle\Entity;


class Payment
{
    private $payidAmount;

    function __construct($payidAmount)
    {
        $this->payidAmount = $payidAmount;
    }

    /**
     * @return mixed
     */
    public function getPayidAmount()
    {
        return $this->payidAmount;
    }
}
