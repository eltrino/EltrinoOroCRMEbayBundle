<?php

namespace Eltrino\EbayBundle\Tests\Controller;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;
use Oro\Bundle\TestFrameworkBundle\Test\ToolsAPI;
use Oro\Bundle\TestFrameworkBundle\Test\Client;

class IntegrationChannelControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient(
            array(),
            array_merge(ToolsAPI::generateBasicHeader('admin', '123123q'), array('HTTP_X-CSRF-Header' => 1))
        );
    }

    public function testEbayChannelUpdateForm()
    {
        $this->markTestSkipped('Should be deleted for the first version');
        return;
        $channel = $this->client
            ->getContainer()
            ->get('doctrine')
            ->getRepository('OroIntegrationBundle:Channel')
            ->findOneBy(array('type' => 'ebay'));

        $updateChannelUrl = $this->client->generate('oro_integration_channel_update', array('id' => $channel->getId()));
        $crawler = $this->client->request('POST', $updateChannelUrl);
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'text/html; charset=UTF-8'));
        $this->assertTrue($crawler->filter('html:contains("Type")')->count() >= 1);
        $this->assertTrue($crawler->filter('html:contains("Name")')->count() >= 1);
        $this->assertTrue($crawler->filter('html:contains("Devid")')->count() >= 1);
        $this->assertTrue($crawler->filter('html:contains("Appid")')->count() >= 1);
        $this->assertTrue($crawler->filter('html:contains("Certid")')->count() >= 1);
        $this->assertTrue($crawler->filter('html:contains("Customer connector")')->count() >= 1);
        $this->assertTrue($crawler->filter('html:contains("Order connector")')->count() >= 1);
    }

    public function testEbayChannelCreateForm()
    {
        $this->markTestSkipped('Should be deleted for the first version');
        return;
        $updateChannelUrl = $this->client->generate('oro_integration_channel_create');
        $crawler = $this->client->request('POST', $updateChannelUrl);

        $response = $this->client->getResponse();

        $form = $crawler->selectButton('Save')->form();
        $form['oro_integration_channel_form[type]']->select('ebay');
        $crawler = $this->client->submit($form);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'text/html; charset=UTF-8'));
        $this->assertTrue($crawler->filter('html:contains("Type")')->count() >= 1);
        $this->assertTrue($crawler->filter('html:contains("Name")')->count() >= 1);
        $this->assertTrue($crawler->filter('html:contains("Devid")')->count() >= 1);
        $this->assertTrue($crawler->filter('html:contains("Appid")')->count() >= 1);
        $this->assertTrue($crawler->filter('html:contains("Certid")')->count() >= 1);
        $this->assertTrue($crawler->filter('html:contains("Customer connector")')->count() >= 1);
        $this->assertTrue($crawler->filter('html:contains("Order connector")')->count() >= 1);
    }

}
