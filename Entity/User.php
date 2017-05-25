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
/**
 * Created by PhpStorm.
 * User: Ruslan Voitenko
 * Date: 4/28/14
 * Time: 2:37 PM
 */

namespace Eltrino\OroCrmEbayBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Oro\Bundle\IntegrationBundle\Model\IntegrationEntityTrait;
use Oro\Bundle\ContactBundle\Entity\Contact;

/**
 * Class User
 * @package Eltrino\OroCrmEbayBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="eltrino_ebay_user")
 */
class User
{
    use IntegrationEntityTrait;

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="user_id", type="string", length=255, nullable=false)
     */
    private $userId;

    /**
     * @var string
     * @ORM\Column(name="eias_token", type="string", length=255, nullable=false, unique=true)
     */
    private $EIASToken;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="Address", mappedBy="user", cascade={"all"})
     */
    private $addresses;

    /**
     * @var Contact
     * @ORM\OneToOne(targetEntity="Oro\Bundle\ContactBundle\Entity\Contact", cascade="persist")
     * @ORM\JoinColumn(name="cotact_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $contact;

    /**
     * @var \DateTime $createdAt
     *
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    public function __construct($userId = null, $EIASToken = null)
    {
        $this->userId = $userId;
        $this->EIASToken = $EIASToken;
        $this->addresses = new ArrayCollection();
        $this->createdAt = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getEIASToken()
    {
        return $this->EIASToken;
    }

    public function getAddresses()
    {
        return new ArrayCollection($this->addresses->toArray());
    }

    public function addAddress(Address $address)
    {
        $existsClosure = function($key, $element) use ($address) {
            if ($element->equal($address)) {
                return true;
            }
            return false;
        };
        if (false === $this->addresses->exists($existsClosure)) {
            $address->assignUser($this);
            $this->addresses->add($address);
        }
    }

    public function setContact(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
