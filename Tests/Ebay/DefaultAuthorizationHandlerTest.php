<?php
/**
 * Created by PhpStorm.
 * User: psw
 * Date: 3/24/14
 * Time: 2:58 PM
 */

namespace Eltrino\OroCrmEbayBundle\Tests\Ebay;

use Eltrino\OroCrmEbayBundle\Ebay\DefaultAuthorizationHandler;

class DefaultAuthorizationHandlerTest extends \PHPUnit_Framework_TestCase
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
