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
 * Date: 5/1/14
 * Time: 3:02 AM
 */

namespace Eltrino\OroCrmEbayBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Address
 * @package Eltrino\OroCrmEbayBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="eltrino_ebay_user_address")
 */
class Address
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="phone", type="string", length=255, nullable=false)
     */
    private $phone;

    /**
     * @var string
     * @ORM\Column(name="street1", type="string", length=255, nullable=false)
     */
    private $street1;

    /**
     * @var string
     * @ORM\Column(name="street2", type="string", length=255, nullable=false)
     */
    private $street2;

    /**
     * @var string
     * @ORM\Column(name="city", type="string", length=255, nullable=false)
     */
    private $city;

    /**
     * @var string
     * @ORM\Column(name="state_or_province", type="string", length=255, nullable=false)
     */
    private $stateOrProvince;

    /**
     * @var string
     * @ORM\Column(name="country", type="string", length=255, nullable=false)
     */
    private $countryCode;

    /**
     * @var string
     * @ORM\Column(name="country_name", type="string", length=255, nullable=false)
     */
    private $countryName;

    /**
     * @var string
     * @ORM\Column(name="postal_code", type="string", length=255, nullable=false)
     */
    private $postalCode;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    public function __construct($name, $phone, $street1, $street2, $city, $stateOrProvince, $countryCode, $countryName, $postalCode)
    {
        $this->name = $name;
        $this->phone = $phone;
        $this->street1 = $street1;
        $this->street2 = $street2;
        $this->city = $city;
        $this->stateOrProvince = $stateOrProvince;
        $this->countryCode = $countryCode;
        $this->countryName = $countryName;
        $this->postalCode = $postalCode;

        $this->firstName = substr($this->getName(), 0, strpos($this->getName(), ' '));
        $this->lastName = substr($this->getName(), strpos($this->getName(), ' '));
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @return string
     */
    public function getStateOrProvince()
    {
        return $this->stateOrProvince;
    }

    /**
     * @return string
     */
    public function getStreet1()
    {
        return $this->street1;
    }

    /**
     * @return string
     */
    public function getStreet2()
    {
        return $this->street2;
    }

    public function assignUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * Compare current and given object if they equal.
     * Two objects equals if passed object the same as current or if all properties (except id) are equl
     * @param Address $address
     * @return bool
     */
    public function equal(Address $address)
    {
        if (spl_object_hash($this) == spl_object_hash($address)) {
            return true;
        }
        if ($this->name == $address->getName()
            && $this->phone == $address->getPhone()
            && $this->street1 == $address->getStreet1()
            && $this->street2 == $address->getStreet2()
            && $this->city == $address->getCity()
            && $this->stateOrProvince == $address->getStateOrProvince()
            && $this->countryCode == $address->getCountryCode()
            && $this->countryName == $address->getCountryName()
            && $this->postalCode == $address->getPostalCode()
        ) {
            return true;
        }

        return false;
    }
}
