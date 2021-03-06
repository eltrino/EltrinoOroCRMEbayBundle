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
namespace Eltrino\OroCrmEbayBundle\Tests\Unit\Provider;

use Eltrino\OroCrmEbayBundle\Provider\EbayChannelType;
use PHPUnit\Framework\TestCase;

class EbayChannelTypeTest extends TestCase
{
    /** @var EbayChannelType */
    private $channel;

    public function setUp()
    {
        $this->channel = new EbayChannelType();
    }

    public function tearDown()
    {
        unset($this->channel);
    }

    public function testPublicInterface()
    {
        $this->assertEquals('Ebay', $this->channel->getLabel());
    }
}
