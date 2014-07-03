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
 * Date: 5/6/14
 * Time: 5:32 PM
 */

namespace Eltrino\OroCrmEbayBundle\Entity;

class UserFactory
{
    public function create($userId, $EIASToken)
    {
        if (false === is_string($userId) || false === is_string($EIASToken)) {
            throw new \InvalidArgumentException('User entity can not be created. One of parameter has wrong type. UserId and EIAS Token should be strings.');
        }
        return new User($userId, $EIASToken);
    }
}
