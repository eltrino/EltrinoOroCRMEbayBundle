<?php
/**
 * Created by PhpStorm.
 * User: Ruslan Voitenko
 * Date: 3/24/14
 * Time: 1:15 PM
 */

namespace Eltrino\EbayBundle\Ebay;

use Eltrino\EbayBundle\Ebay\Api\AuthorizationHandler;

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
