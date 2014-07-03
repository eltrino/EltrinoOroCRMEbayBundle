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
namespace Eltrino\OroCrmEbayBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\ParameterBag;
use Oro\Bundle\IntegrationBundle\Entity\Transport;

/**
 * Class EbayRestTransport
 * @package Eltrino\OroCrmEbayBundle\Entity
 * @ORM\Entity()
 */
class EbayRestTransport extends Transport
{
    /**
     * @var string
     *
     * @ORM\Column(name="wsdl_url", type="string", length=255, nullable=false)
     */
    private $wsdlUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="auth_token", type="string", length=2048, nullable=false)
     */
    private $authToken;

    /**
     * @var string
     *
     * @ORM\Column(name="dev_id", type="string", length=255, nullable=false)
     */
    private $devId;

    /**
     * @var string
     *
     * @ORM\Column(name="app_id", type="string", length=255, nullable=false)
     */
    private $appId;

    /**
     * @var string
     *
     * @ORM\Column(name="cert_id", type="string", length=255, nullable=false)
     */
    private $certId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sync_start_date", type="date")
     */
    private $syncStartDate;

    public function __construct()
    {
        $this->syncStartDate = new \DateTime('2007-01-01', new \DateTimeZone('UTC'));
    }

    /**
     * @return string
     */
    public function getWsdlUrl()
    {
        return $this->wsdlUrl;
    }

    /**
     * @param string $wsdlUrl
     * @return $this
     */
    public function setWsdlUrl($wsdlUrl)
    {
        $this->wsdlUrl = $wsdlUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthToken()
    {
        return $this->authToken;
    }

    /**
     * @param string $authToken
     * @return $this
     */
    public function setAuthToken($authToken)
    {
        $this->authToken = $authToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getDevId()
    {
        return $this->devId;
    }

    /**
     * @param $devId
     * @return $this
     */
    public function setDevId($devId)
    {
        $this->devId = $devId;

        return $this;
    }

    /**
     * @return string
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * @param $appId
     * @return $this
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;

        return $this;
    }

    /**
     * @return string
     */
    public function getCertId()
    {
        return $this->certId;
    }

    /**
     * @param $appId
     * @return $this
     */
    public function setCertId($certId)
    {
        $this->certId = $certId;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSyncStartDate()
    {
        return $this->syncStartDate;
    }

    /**
     * @param \DateTime $syncStartDate
     *
     * @return $this
     */
    public function setSyncStartDate(\DateTime $syncStartDate = null)
    {
        $this->syncStartDate = $syncStartDate;

        return $this;
    }

    /**
     * @param $devId
     * @param $appId
     * @param $certId
     */
    public function create($devId, $appId, $certId)
    {
        $this->devId = $devId;
        $this->appId = $appId;
        $this->certId = $certId;
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingsBag()
    {
        return new ParameterBag(
            [
                'auth_token'      => $this->getAuthToken(),
                'wsdl_url'        => $this->getWsdlUrl(),
                'dev_id'          => $this->getDevId(),
                'app_id'          => $this->getAppId(),
                'cert_id'         => $this->getCertId(),
                'wsdl_url'        => $this->getWsdlUrl(),
                'start_sync_date' => $this->getSyncStartDate(),
            ]
        );
    }
}
