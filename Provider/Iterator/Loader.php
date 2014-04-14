<?php
/**
 * Created by PhpStorm.
 * User: psw
 * Date: 4/10/14
 * Time: 4:58 PM
 */

namespace Eltrino\OroCrmEbayBundle\Provider\Iterator;

use Eltrino\OroCrmEbayBundle\Provider\Iterator\LoaderReachedEndException;

interface Loader
{
    /**
     * @return mixed
     */
    function load();
}
