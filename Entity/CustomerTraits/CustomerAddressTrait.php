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
namespace Eltrino\OroCrmEbayBundle\Entity\CustomerTraits;

use Eltrino\OroCrmEbayBundle\Model\Customer\CustomerAddress;

trait CustomerAddressTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="street1", type="string", length=512, nullable=true)
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
     * @ORM\Column(name="city", type="string", length=128, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state_or_province", type="string", length=128, nullable=true)
     */
    private $stateOrProvince;

    /**
     * @var string
     *
     * @ORM\Column(name="country_name", type="string", length=300, nullable=true)
     */
    private $countryName;

    /**
     * @var string
     *
     * @ORM\Column(name="postal_code", type="string", length=24, nullable=true)
     */
    private $postalCode;

    /**
     * @return CustomerAddress
     */
    protected function initCustomerAddress()
    {
        return new CustomerAddress($this->street1, $this->street2, $this->city, $this->stateOrProvince,
            $this->countryName, $this->postalCode);
    }

    /**
     * @param CustomerAddress $customerAddress
     */
    protected function initFromCustomerAddress(CustomerAddress $customerAddress)
    {
        $this->street1         = $customerAddress->getStreet1();
        $this->street2         = $customerAddress->getStreet2();
        $this->city            = $customerAddress->getCity();
        $this->stateOrProvince = $customerAddress->getStateOrProvince();
        $this->countryName     = $customerAddress->getCountryName();
        $this->postalCode      = $customerAddress->getPostalCode();
    }
}
