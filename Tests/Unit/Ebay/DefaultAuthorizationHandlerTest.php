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
namespace Eltrino\OroCrmEbayBundle\Tests\Unit\Ebay;

use Eltrino\OroCrmEbayBundle\Ebay\DefaultAuthorizationHandler;
use PHPUnit\Framework\TestCase;

class DefaultAuthorizationHandlerTest extends TestCase
{
    const DUMMY_DEV_ID     = 'dummy_dev_id';
    const DUMMY_APP_ID     = 'dummy_app_id';
    const DUMMY_CERT_ID    = 'dummy_cert_id';
    const DUMMY_AUTH_TOKEN = 'dummy_auth_token';

    /**
     * @var DefaultAuthorizationHandler
     */
    private $authHandler;

    protected function setUp()
    {
        $this->authHandler = new DefaultAuthorizationHandler(
            self::DUMMY_DEV_ID,
            self::DUMMY_APP_ID,
            self::DUMMY_CERT_ID,
            self::DUMMY_AUTH_TOKEN
        );
    }

    public function testGetDevId()
    {
        $this->assertEquals(self::DUMMY_DEV_ID, $this->authHandler->getDevId());
    }

    public function testGetAppId()
    {
        $this->assertEquals(self::DUMMY_APP_ID, $this->authHandler->getAppId());
    }

    public function testGetCertId()
    {
        $this->assertEquals(self::DUMMY_CERT_ID, $this->authHandler->getCertId());
    }

    public function testGetAuthToken()
    {
        $this->assertEquals(self::DUMMY_AUTH_TOKEN, $this->authHandler->getAuthToken());
    }
}
