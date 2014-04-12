<?php
/**
 * Created by PhpStorm.
 * User: psw
 * Date: 4/10/14
 * Time: 4:58 PM
 */

namespace Eltrino\EbayBundle\Provider\Iterator;

use Eltrino\EbayBundle\Provider\Iterator\LoaderReachedEndException;

interface Loader
{
    /**
     * @return mixed
     */
    function load();
}
