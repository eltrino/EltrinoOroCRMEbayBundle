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
 * Time: 7:07 PM
 */

namespace Eltrino\OroCrmEbayBundle\ImportExport\Strategy;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Eltrino\OroCrmEbayBundle\Entity\Address;
use Eltrino\OroCrmEbayBundle\Entity\User;
use Eltrino\OroCrmEbayBundle\Provider\EbayBuyerConnector;
use Oro\Bundle\AddressBundle\Entity\Country;
use Oro\Bundle\AddressBundle\Entity\Region;
use Oro\Bundle\ImportExportBundle\Context\ContextInterface;
use Oro\Bundle\ImportExportBundle\Strategy\Import\ImportStrategyHelper;
use Oro\Bundle\ImportExportBundle\Strategy\StrategyInterface;
use Oro\Bundle\ImportExportBundle\Context\ContextAwareInterface;
use Oro\Bundle\ContactBundle\Entity\Contact;
use Oro\Bundle\ContactBundle\Entity\ContactAddress;
use Oro\Bundle\ContactBundle\Entity\ContactPhone;


class UserStrategy implements StrategyInterface, ContextAwareInterface
{
    /**
     * @var ContextInterface
     */
    private $context;

    /**
     * @var ImportStrategyHelper
     */
    private $strategyHelper;

    public function __construct(ImportStrategyHelper $strategyHelper)
    {
        $this->strategyHelper = $strategyHelper;
    }

    /**
     * Process entity according to current strategy
     * Return either updated entity, or null if entity must not be used
     *
     * @param User $entity
     * @return User|mixed|null
     */
    public function process($entity)
    {
        $criteria = ['EIASToken' => $entity->getEIASToken(), 'channel' => $entity->getChannel()];
        $class = EbayBuyerConnector::USER_ENTITY;
        $repository = $this
            ->strategyHelper
            ->getEntityManager($class)
            ->getRepository($class);
        /** @var User $user */
        $user = $repository->findOneBy($criteria);
        if ($user) {
            $this->strategyHelper->importEntity($user, $entity, array('id', 'EIASToken', 'addresses', 'contact'));
            foreach ($entity->getAddresses() as $address) {
                $user->addAddress($address);
            }
        } else {
            $user = $entity;
        }

        try {
            $this->processContact($user);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this->validateAndUpdateContext($user);
    }

    private function processContact(User $user)
    {
        $contact = $user->getContact();
        if (is_null($contact)) {
            /** @var Address $address */
            $address = $user->getAddresses()->get(0);// get first address, consider it is primary one

            if (!$address) {
                throw new \Exception('Contact can not be created. Ebay User has no at least one address');
            }

            $phone = new ContactPhone($address->getPhone());
            $phone->setPrimary(true);

            $contact = new Contact();
            $contact->setFirstName($address->getFirstName());
            $contact->setLastName($address->getLastName());
            $contact->addPhone($phone);

            $user->setContact($contact);
        }

        /** @var Address[] $user->getAddresses() */
        foreach ($user->getAddresses() as $address) {
            if ($address->getId()) {
                continue;
            }

            $country = $this->extractCountry($address->getCountryCode());
            $region = null;
            if (!is_null($country)) {
                $region  = $this->extractRegion($country, $address->getStateOrProvince());
            }

            $contactAddress = new ContactAddress();
            $contactAddress->setFirstName($address->getFirstName());
            $contactAddress->setLastName($address->getLastName());
            $contactAddress->setCountry($country);
            $contactAddress->setRegion($region);
            $contactAddress->setCity($address->getCity());
            $contactAddress->setStreet($address->getStreet1());
            $contactAddress->setStreet2($address->getStreet2());
            $contactAddress->setPostalCode($address->getPostalCode());

            $contact->addAddress($contactAddress);
            if (is_null($contact->getPrimaryAddress())) {
                $contact->setPrimaryAddress($contactAddress);
            }
        }
    }

    /**
     * Retrieves Country
     * @param string $countryCode
     * @return Country
     */
    private function extractCountry($countryCode)
    {
        /** @var EntityRepository $repository */
        $repository = $this
            ->strategyHelper
            ->getEntityManager('Oro\Bundle\AddressBundle\Entity\Country')
            ->getRepository('Oro\Bundle\AddressBundle\Entity\Country');

        /** @var Country $country */
        $country = $repository->findOneBy(array('iso2Code' => $countryCode));
        return $country;
    }

    /**
     * Retrieves Region
     * @param Country $country
     * @param string $stateOrProvince
     * @return Region
     */
    private function extractRegion(Country $country, $stateOrProvince)
    {
        $region = $country->getRegions()->filter(function(Region $region) use($stateOrProvince) {
            if ($region->getCode() == $stateOrProvince) {
                return $region;
            }
        })->first();

        if (!$region) {
            $region = null;
        }
        return $region;
    }

    /**
     * @param ContextInterface $context
     */
    public function setImportExportContext(ContextInterface $context)
    {
        $this->context = $context;
    }

    /**
     * @param object $entity
     *
     * @return null|object
     */
    private function validateAndUpdateContext($entity)
    {
        // validate entity
        $validationErrors = $this->strategyHelper->validateEntity($entity);
        if ($validationErrors) {
            $this->context->incrementErrorEntriesCount();
            $this->strategyHelper->addValidationErrors($validationErrors, $this->context);

            return null;
        }

        // increment context counter
        if ($entity->getId()) {
            $this->context->incrementUpdateCount();
        } else {
            $this->context->incrementAddCount();
        }

        return $entity;
    }
}
