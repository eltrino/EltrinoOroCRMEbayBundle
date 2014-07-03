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
 * Date: 5/22/14
 * Time: 5:43 PM
 */

namespace Eltrino\OroCrmEbayBundle\Tests\Entity;

use Eltrino\OroCrmEbayBundle\Entity\Address;

class AddressTest extends \PHPUnit_Framework_TestCase
{
    public function testEqual()
    {
        $address1 = new Address('First Last', '123456789', 'Street 1', 'Street 2', 'City', 'State', 'CountryCode', 'CountryName', '75200');
        $address2 = new Address('First Anotherlast', '123456789', 'Street 1', 'Street 2', 'City', 'State', 'CountryCode', 'CountryName', '75200');
        $address3 = new Address('First Anotherlast', '123456789', 'Street 1', 'Street 2', 'City', 'State', 'CountryCode', 'CountryName', '75200');

        $this->assertFalse($address1->equal($address2));
        $this->assertFalse($address1->equal($address3));
        $this->assertTrue($address2->equal($address3));
    }
}
