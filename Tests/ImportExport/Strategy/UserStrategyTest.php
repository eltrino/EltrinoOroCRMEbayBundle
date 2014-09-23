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
 * Date: 6/4/14
 * Time: 6:02 PM
 */

namespace Eltrino\OroCrmEbayBundle\Tests\ImportExport\Srtategy;

use Eltrino\OroCrmEbayBundle\Entity\Address;
use Eltrino\OroCrmEbayBundle\Entity\User;
use Eltrino\OroCrmEbayBundle\ImportExport\Strategy\UserStrategy;
use Oro\Bundle\AddressBundle\Entity\Country;
use Oro\Bundle\AddressBundle\Entity\Region;
use OroCRM\Bundle\ContactBundle\Entity\Contact;

class UserStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Oro\Bundle\ImportExportBundle\Strategy\Import\ImportStrategyHelper
     * @Mock Oro\Bundle\ImportExportBundle\Strategy\Import\ImportStrategyHelper
     */
    private $strategyHelper;

    /**
     * @var \Doctrine\ORM\EntityManager
     * @Mock Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     * @Mock Doctrine\Common\Persistence\ObjectRepository
     */
    private $repository;

    /**
     * @var \Oro\Bundle\ImportExportBundle\Context\Context
     * @Mock \Oro\Bundle\ImportExportBundle\Context\Context
     */
    private $context;

    protected function setUp()
    {
        \Eltrino\PHPUnit\MockAnnotations\MockAnnotations::init($this);

        $this->strategyHelper
            ->expects($this->any())
            ->method('getEntityManager')
            ->will($this->returnValue($this->em));

        $this->em
            ->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($this->repository));
    }

    public function testProcessWhenImportedUserIsNew()
    {
        $strategy = new UserStrategy($this->strategyHelper);
        $strategy->setImportExportContext($this->context);

        $this->repository
            ->expects($this->at(0))
            ->method('findOneBy')
            ->will($this->returnValue(null));

        $this->repository
            ->expects($this->at(1))
            ->method('findOneBy')
            ->will($this->returnValue(new Country('US')));

        $this->context
            ->expects($this->atLeastOnce())
            ->method('incrementAddCount');

        $user = $strategy->process($this->dummyUser());

        $this->assertCount(1, $user->getAddresses());
        $this->assertNotNull($user->getContact());
        $this->assertCount(1, $user->getContact()->getAddresses());
    }

    public function testProcessWhenImportedUserIsAlreadyExistsInSystem()
    {
        $strategy = new UserStrategy($this->strategyHelper);
        $strategy->setImportExportContext($this->context);

        $dummyUser = $this->dummyUser();
        $contact = new Contact();
        $dummyUser->setContact($contact);

        $region = new Region('DummyState');
        $region->setCode('DummyState');
        $country = new Country('US');
        $country->addRegion($region);

        $this->repository
            ->expects($this->at(0))
            ->method('findOneBy')
            ->will($this->returnValue($dummyUser));

        $this->repository
            ->expects($this->at(1))
            ->method('findOneBy')
            ->will($this->returnValue($country));

        $this->repository
            ->expects($this->at(2))
            ->method('findOneBy')
            ->will($this->returnValue($country));

        $dummyUserWithExtraAddress = $this->dummyUser();
        $dummyUserWithExtraAddress->addAddress(new Address('Another Address', '987654321', 'Street 11', 'Street 22', 'City', 'DummyState', 'CountryCode', 'CountryName', '75200'));

        $user = $strategy->process($dummyUserWithExtraAddress);

        $this->assertCount(2, $user->getAddresses());
        $this->assertNotNull($user->getContact());
        $this->assertCount(2, $user->getContact()->getAddresses());
    }

    public function testStrategyReturnsNullIfValidationErrorsOccur()
    {
        $strategy = new UserStrategy($this->strategyHelper);
        $strategy->setImportExportContext($this->context);

        $dummyUser = $this->dummyUser();
        $contact = new Contact();
        $dummyUser->setContact($contact);

        $region = new Region('DummyState');
        $region->setCode('DummyState');
        $country = new Country('US');
        $country->addRegion($region);

        $this->repository
            ->expects($this->at(0))
            ->method('findOneBy')
            ->will($this->returnValue($dummyUser));

        $this->repository
            ->expects($this->at(1))
            ->method('findOneBy')
            ->will($this->returnValue($country));

        $this->strategyHelper
            ->expects($this->once())
            ->method('validateEntity')
            ->with($dummyUser)
            ->will($this->returnValue(array('errors')));

        $this->context
            ->expects($this->once())
            ->method('incrementErrorEntriesCount');

        $this->strategyHelper
            ->expects($this->atLeastOnce())
            ->method('addValidationErrors')
            ->with(array('errors'), $this->context);

        $user = $strategy->process($this->dummyUser());

        $this->assertNull($user);
    }

    /**
     * @return User
     */
    private function dummyUser()
    {
        $user = new User('user_id', 'eias_token');
        $address = new Address('First Last', '123456789', 'Street 1', 'Street 2', 'City', 'DummyState', 'CountryCode', 'CountryName', '75200');
        $user->addAddress($address);
        return $user;
    }
}
