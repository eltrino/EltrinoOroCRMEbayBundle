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
 * User: psw
 * Date: 4/25/14
 * Time: 4:07 PM
 */

namespace Eltrino\OroCrmEbayBundle\Tests\Provider\Iterator\User;

use Eltrino\OroCrmEbayBundle\Provider\Iterator\User\UsersLoader;
use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

class UsersLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @Mock Eltrino\OroCrmEbayBundle\Provider\Iterator\Order\InitialModeLoader
     */
    private $ordersLoader;

    protected function setUp()
    {
        MockAnnotations::init($this);
    }

    public function testLoad()
    {
        $orders = [
            $this->getDummyOrder(),
            $this->getDummyIncorrectOrder1(),
            $this->getDummyIncorrectOrder2()
        ];

        $this->ordersLoader
            ->expects($this->once())
            ->method('load')
            ->will($this->returnValue($orders));

        $loader = new UsersLoader($this->ordersLoader);
        $loadedUsers = $loader->load();

        $this->assertNotNull($loadedUsers);
        $this->assertCount(1, $loadedUsers);

        $this->assertEquals($this->getDummyUser(), $loadedUsers[0]);
    }

    private function getDummyOrder()
    {
        return new \SimpleXMLElement('<Order>
            <OrderId>1</OrderId>
            <BuyerUserID>dummy_user_id_1</BuyerUserID>
            <EIASToken>token_1</EIASToken>
            <ShippingAddress>
                <AddressID>12345</AddressID>
                <Name>Dummy Name</Name>
                <City>Dummy City</City>
            </ShippingAddress>
        </Order>');
    }

    private function getDummyIncorrectOrder1()
    {
        return new \SimpleXMLElement('<Order>
            <OrderId>2</OrderId>
            <EIASToken>token_2</EIASToken>
            <ShippingAddress>
                <Name>Dummy name</Name>
            </ShippingAddress>
        </Order>');
    }

    private function getDummyIncorrectOrder2()
    {
        return new \SimpleXMLElement('<Order>
            <OrderId>2</OrderId>
            <BuyerUserID>user_id</BuyerUserID>
            <ShippingAddress>
                <City>Dummy City</City>
            </ShippingAddress>
        </Order>');
    }

    /**
     * @return \SimpleXMLElement
     */
    private function getDummyUser()
    {
        return new \SimpleXMLElement('<User>
            <UserID>dummy_user_id_1</UserID>
            <EIASToken>token_1</EIASToken>
            <Address>
                <AddressID12345>
                    <AddressID>12345</AddressID>
                    <Name>Dummy Name</Name>
                    <City>Dummy City</City>
                </AddressID12345>
            </Address>
        </User>');
    }
}
