<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 4/8/14
 * Time: 2:08 PM
 */

namespace Eltrino\OroCrmEbayBundle\Model\Customer;

class CustomerAddress
{
    /**
     * @var string
     *
     * @ORM\Column(name="street1", type="string", length=512)
     */
    private $street1;

    /**
     * @var string
     *
     * @ORM\Column(name="street2", type="string", length=512, nullable=true)
     */
    private $street2;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=128)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state_or_province", type="string", length=128)
     */
    private $stateOrProvince;

    /**
     * @var string
     *
     * @ORM\Column(name="country_name", type="string", length=300)
     */
    private $countryName;

    /**
     * @var string
     *
     * @ORM\Column(name="postal_code", type="string", length=24)
     */
    private $postalCode;

    /**
     * @param $street1
     * @param $street2
     * @param $city
     * @param $stateOrProvince
     * @param $countryName
     * @param $postalCode
     */
    public function __construct($street1, $street2, $city, $stateOrProvince, $countryName, $postalCode)
    {
        $this->street1         = $street1;
        $this->street2         = $street2;
        $this->city            = $city;
        $this->stateOrProvince = $stateOrProvince;
        $this->countryName     = $countryName;
        $this->postalCode      = $postalCode;
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


}
