<?php
/*
 * Copyright (c) 2014 Eltrino LLC (http://eltrino.com)
 *
 * Licensed under the Open Software License (OSL 3.0).
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://opensource.org/licenses/osl-3.0.php
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@eltrino.com so we can send you a copy immediately.
 */
namespace Eltrino\OroCrmEbayBundle\Ebay;

use Eltrino\OroCrmEbayBundle\Ebay\Api\AuthorizationHandler;

class DefaultAuthorizationHandler implements AuthorizationHandler
{
    /**
     * @var string
     */
    private $devId;

    /**
     * @var string
     */
    private $appId;

    /**
     * @var string
     */
    private $certId;

    /**
     * @var string
     */
    private $authToken;

    function __construct($devId, $appId, $certId, $authToken)
    {
        $this->devId = $devId;
        $this->appId = $appId;
        $this->certId = $certId;
        $this->authToken = $authToken;
    }

    /**
     * Retrieves dev id
     * @return string
     */
    function getDevId()
    {
        return $this->devId;
    }

    /**
     * Retrieves app id
     * @return string
     */
    function getAppId()
    {
        return $this->appId;
    }

    /**
     * Retrieves cert id
     * @return string
     */
    function getCertId()
    {
        return $this->certId;
    }

    /**
     * Retrieves auth token
     * @return string
     * @inheritdoc
     */
    function getAuthToken()
    {
        return $this->authToken;
    }
}
