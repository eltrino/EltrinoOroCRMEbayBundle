<?php

namespace Eltrino\EbayBundle\Migrations\DataFixtures\ORM\v1_0;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use Oro\Bundle\IntegrationBundle\Entity\Channel;
use Eltrino\EbayBundle\Entity\EbayRestTransport;

class LoadDemoEbayData extends AbstractFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $transport = new EbayRestTransport();
        $transport->create(
            'dev_id',
            'app_id',
            'cert_id'
        );

        $manager->persist($transport);

        $channel = new Channel();
        $channel->setType('ebay');
        $channel->setConnectors(['order']);
        $channel->setName('Ebay');
        $channel->setTransport($transport);

        $manager->persist($channel);
        $manager->flush();
    }

}
