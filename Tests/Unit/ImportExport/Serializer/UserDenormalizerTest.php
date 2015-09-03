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
namespace Eltrino\OroCrmEbayBundle\Tests\Unit\ImportExport\Serializer;

use Doctrine\ORM\EntityManagerInterface;
use Eltrino\OroCrmEbayBundle\Entity\Address;
use Eltrino\OroCrmEbayBundle\Entity\AddressFactory;
use Eltrino\OroCrmEbayBundle\Entity\User;
use Eltrino\OroCrmEbayBundle\Entity\UserFactory;
use Eltrino\OroCrmEbayBundle\ImportExport\Serializer\UserDenormalizer;
use Oro\Bundle\IntegrationBundle\Entity\Channel;
use Oro\Bundle\IntegrationBundle\Entity\Repository\ChannelRepository;
use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

class UserDenormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ChannelRepository
     * @Mock Oro\Bundle\IntegrationBundle\Entity\Repository\ChannelRepository
     */
    private $channelRepository;

    /**
     * @var EntityManagerInterface
     * @Mock Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserFactory
     * @Mock Eltrino\OroCrmEbayBundle\Entity\UserFactory
     */
    private $userFactory;

    /**
     * @var AddressFactory
     * @Mock Eltrino\OroCrmEbayBundle\Entity\AddressFactory
     */
    private $addressFactory;

    /**
     * @var UserDenormalizer
     */
    private $denormalizer;

    protected function setUp()
    {
        MockAnnotations::init($this);
        $this->denormalizer = new UserDenormalizer($this->entityManager, $this->userFactory, $this->addressFactory);
    }

    public function testSupportsDenormalization()
    {
        $data = new \SimpleXMLElement('<a>b</a>');
        $type = 'Eltrino\OroCrmEbayBundle\Entity\User';
        $this->assertTrue($this->denormalizer->supportsDenormalization($data, $type));
        $this->assertFalse($this->denormalizer->supportsDenormalization(new \StdClass(), $type));
        $this->assertFalse($this->denormalizer->supportsDenormalization($data, 'dummy\type'));
        $this->assertFalse($this->denormalizer->supportsDenormalization(new \StdClass(), 'dummy\type'));
    }

    public function testDenormalize()
    {
        $user = new User('user_id', 'token');
        $address = new Address('First Last', '123456789', 'street_one', 'street_two', 'BigCity', 'LL', 'CountryCode', 'CountryName', '75200');

        $data = new \SimpleXMLElement('<User>
            <UserID>user_id</UserID>
            <EIASToken>token</EIASToken>
            <Address>
                <AddressID12345>
                    <AddressID>12345</AddressID>
                    <Name>First Last</Name>
                    <Street1>street_one</Street1>
                    <Street2>street_two</Street2>
                    <CityName>BigCity</CityName>
                    <StateOrProvince>LL</StateOrProvince>
                    <Country>CountryCode</Country>
                    <CountryName>CountryName</CountryName>
                    <Phone>123456789</Phone>
                    <PostalCode>75200</PostalCode>
                </AddressID12345>
            </Address>
        </User>');

        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo('OroIntegrationBundle:Channel'))
            ->will($this->returnValue($this->channelRepository));

        $this->channelRepository
            ->expects($this->once())
            ->method('getOrLoadById')
            ->with($this->equalTo('channel_identificator'))
            ->will($this->returnValue(new Channel()));

        $this->userFactory
            ->expects($this->once())
            ->method('create')
            ->with($this->equalTo('user_id'), $this->equalTo('token'))
            ->will($this->returnValue($user));

        $this->addressFactory
            ->expects($this->once())
            ->method('create')
            ->with(
                $this->equalTo('First Last'), $this->equalTo('123456789'), $this->equalTo('street_one'),
                $this->equalTo('street_two'), $this->equalTo('BigCity'), $this->equalTo('LL'),
                $this->equalTo('CountryCode'), $this->equalTo('CountryName'), $this->equalTo('75200')
            )
            ->will($this->returnValue($address));

        $denormalizedUser = $this->denormalizer->denormalize($data, '', null, array('channel' => 'channel_identificator'));

        $this->assertNotNull($denormalizedUser);
        $this->assertEquals($user, $denormalizedUser);

        $addresses = $user->getAddresses();
        $this->assertCount(1, $addresses);
        /** @var Address $address1 */
        $address1 = $addresses->current();
        $this->assertEquals($address, $address1);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Context should contain reference to channel
     */
    public function testDenormalizeException()
    {
        $this->denormalizer->denormalize(new \SimpleXMLElement('<a>b</a>'), '', null, array());
    }
}
