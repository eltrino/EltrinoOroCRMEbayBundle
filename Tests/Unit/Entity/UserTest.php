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
namespace Eltrino\OroCrmEbayBundle\Tests\Unit\Entity;

use Eltrino\OroCrmEbayBundle\Entity\Address;
use Eltrino\OroCrmEbayBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testAddAddress()
    {
        $address1 = new Address('First Last', '123456789', 'Street 1', 'Street 2', 'City', 'State', 'CountryCode', 'CountryName', '75200');
        $address2 = new Address('First Anotherlast', '123456789', 'Street 1', 'Street 2', 'City', 'State', 'CountryCode', 'CountryName', '75200');
        $address3 = new Address('First Anotherlast', '123456789', 'Street 1', 'Street 2', 'City', 'State', 'CountryCode', 'CountryName', '75200');

        $user = new User();
        $user->addAddress($address1);
        $user->addAddress($address2);
        $user->addAddress($address3);

        $this->assertCount(2, $user->getAddresses());
        $this->assertSame($user, $address1->getUser());
        $this->assertSame($user, $address2->getUser());
    }
}
