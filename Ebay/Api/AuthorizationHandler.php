<?php
/**
 * Created by PhpStorm.
 * User: psw
 * Date: 3/24/14
 * Time: 12:32 PM
 */

namespace Eltrino\OroCrmEbayBundle\Ebay\Api;

interface AuthorizationHandler
{
    /**
     * Retrieves dev id
     * @return string
     */
    function getDevId();

    /**
     * Retrieves app id
     * @return string
     */
    function getAppId();

    /**
     * Retrieves cert id
     * @return string
     */
    function getCertId();

    /**
     * Retrieves auth token
     * @return string
     */
    function getAuthToken();
}
