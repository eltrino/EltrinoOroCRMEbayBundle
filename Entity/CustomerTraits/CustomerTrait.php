<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 4/9/14
 * Time: 11:50 AM
 */

namespace Eltrino\EbayBundle\Entity\CustomerTraits;

trait CustomerTrait
{
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
    public function getCountryName()
    {
        return $this->countryName;
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
} 