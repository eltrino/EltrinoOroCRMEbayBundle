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
namespace Eltrino\OroCrmEbayBundle\Provider\Iterator\User;

use Eltrino\OroCrmEbayBundle\Provider\Iterator\Loader;
use Eltrino\OroCrmEbayBundle\Provider\Iterator\Order\OrderLoader;

class UsersLoader implements Loader
{
    /**
     * @var \Eltrino\OroCrmEbayBundle\Provider\Iterator\Order\OrderLoader
     */
    private $ordersLoader;

    public function __construct(OrderLoader $ordersLoader)
    {
        $this->ordersLoader = $ordersLoader;
    }

    /**
     * Gather unique buyers (ebay users) from orders
     * @return array
     */
    public function load()
    {
        $users = array();
        do {
            $continue = false;
            $orders = $this->ordersLoader->load();
            if (empty($orders)) {
                break;
            }
            $users = $this->buildUsersXmlData($this->extractUsers($orders));
            if (empty($users)) {
                $continue = true;
            }
        } while($continue);
        return $users;
    }

    /**
     * Extract users and its shipping address from orders xml data.
     * @param array $orders
     * @return array
     */
    private function extractUsers(array $orders)
    {
        $users = [];
        foreach ($orders as $order) {
            $userId = $order->BuyerUserID->__toString();
            $EIASToken = $order->EIASToken->__toString();

            if (!$userId || !$EIASToken) {
                continue;
            }

            if (!isset($users[$EIASToken])) {
                $user = [
                    'UserID' => $userId,
                    'EIASToken' => $EIASToken,
                    'ShippingAddress' => []
                ];
            } else {
                $user = $users[$EIASToken];
            }

            $addressID = $order->ShippingAddress->AddressID->__toString();
            if (!$addressID || isset($user['ShippingAddress'][$addressID])) {
                // if AddressID is not exists, or user with particular address already extracted
                continue;
            }

            $shippingAddress = [];
            foreach ($order->ShippingAddress->children() as $child) {
                $shippingAddress[$child->getName()] = $child->__toString();
            }
            $user['ShippingAddress'][$addressID] = $shippingAddress;

            $users[$EIASToken] = $user;
        }
        return $users;
    }

    /**
     * Build Users XML data appropriate for Users Denormalizer from given array of users
     * @param array $users
     * @return array
     */
    private function buildUsersXmlData(array $users)
    {
        $usersXmlData = [];
        foreach ($users as $each) {
            $userXmlData = new \SimpleXMLElement('<User></User>');
            $userXmlData->addChild('UserID', $each['UserID']);
            $userXmlData->addChild('EIASToken', $each['EIASToken']);
            $addresses = $userXmlData->addChild('Address');
            foreach ($each['ShippingAddress'] as $addressID => $addressParts) {
                $address = $addresses->addChild('AddressID' . $addressID);
                foreach ($addressParts as $name => $value) {
                    $address->addChild($name, $value);
                }
            }
            $usersXmlData[] = $userXmlData;
        }
        return $usersXmlData;
    }
}
