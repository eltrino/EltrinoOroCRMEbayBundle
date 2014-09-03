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
 * Date: 4/30/14
 * Time: 6:35 PM
 */

namespace Eltrino\OroCrmEbayBundle\ImportExport\Serializer;

use Doctrine\ORM\EntityManagerInterface;
use Eltrino\OroCrmEbayBundle\Entity\AddressFactory;
use Eltrino\OroCrmEbayBundle\Entity\User;
use Eltrino\OroCrmEbayBundle\Entity\UserFactory;
use Eltrino\OroCrmEbayBundle\Provider\EbayBuyerConnector;
use Oro\Bundle\ImportExportBundle\Serializer\Normalizer\DenormalizerInterface;

class UserDenormalizer implements DenormalizerInterface
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Entity\UserFactory
     */
    private $userFactory;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Entity\AddressFactory
     */
    private $addressFactory;

    public function __construct(EntityManagerInterface $em, UserFactory $userFactory, AddressFactory $addressFactory)
    {
        $this->em = $em;
        $this->userFactory = $userFactory;
        $this->addressFactory = $addressFactory;
    }

    /**
     * Denormalizes data back into an object of the given class
     *
     * @param mixed $data data to restore
     * @param string $class the expected class to instantiate
     * @param string $format format the given data was extracted from
     * @param array $context options available to the denormalizer
     *
     * @return User
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        if (!isset($context['channel'])) {
            throw new \LogicException('Context should contain reference to channel');
        }

        $user = $this->userFactory->create((string) $data->UserID, (string) $data->EIASToken);

        foreach ($data->Address->children() as $addressData) {
            $name            = (string) $addressData->Name;
            $street1         = (string) $addressData->Street1;
            $street2         = (string) $addressData->Street2;
            $city            = (string) $addressData->CityName;
            $stateOrProvince = (string) $addressData->StateOrProvince;
            $countryCode     = (string) $addressData->Country;
            $countryName     = (string) $addressData->CountryName;
            $phone           = (string) $addressData->Phone;
            $postalCode      = (string) $addressData->PostalCode;

            $address = $this->addressFactory->create($name, $phone, $street1, $street2,
                $city, $stateOrProvince, $countryCode, $countryName, $postalCode);

            $user->addAddress($address);
        }

        $channel = $this
            ->em
            ->getRepository('OroIntegrationBundle:Channel')
            ->getOrLoadById($context['channel'])
        ;
        $user->setChannel($channel);

        return $user;
    }

    /**
     * Checks whether the given class is supported for denormalization by this normalizer
     *
     * @param mixed $data Data to denormalize from.
     * @param string $type The class to which the data should be denormalized.
     * @param string $format The format being deserialized from.
     * @param array $context
     * @return Boolean
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = array())
    {
        return (is_object($data) && $data instanceof \SimpleXMLElement) && ($type == EbayBuyerConnector::USER_ENTITY);
    }
}
