<?php
/**
 * Created by PhpStorm.
 * User: psw
 * Date: 3/21/14
 * Time: 4:37 PM
 */

namespace Eltrino\OroCrmEbayBundle\Tests\Ebay;

use Eltrino\OroCrmEbayBundle\Ebay\CheckRestClientImpl;

class CheckRestClientImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Guzzle\Http\ClientInterface
     */
    private $client;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Ebay\Api\AuthorizationHandler
     */
    private $authHandler;

    /**
     * @var \Guzzle\Http\Message\RequestInterface
     */
    private $request;

    /**
     * @var \Guzzle\Http\Message\Response
     */
    private $response;

    /**
     * @var \SimpleXmlElement
     */
    private $responseXml;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Ebay\Filters\Filter
     */
    private $filter;

    /**
     * @var CheckRestClientImpl
     */
    private $checkRestClient;

    public function setUp()
    {
        $this->client = $this->getMockBuilder('Guzzle\Http\ClientInterface')
            ->getMock();
        $this->authHandler = $this->getMockBuilder('Eltrino\OroCrmEbayBundle\Ebay\Api\AuthorizationHandler')
            ->getMock();
        $this->request = $this->getMockBuilder('Guzzle\Http\Message\RequestInterface')
            ->getMock();
        $this->response = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $this->responseXml = new \SimpleXMLElement('<GeteBayOfficialTimeResponse xmlns="urn:ebay:apis:eBLBaseComponents"><Ack>Success</Ack></GeteBayOfficialTimeResponse>');

        $this->checkRestClient = new CheckRestClientImpl($this->client, $this->authHandler);
        $this->filter = $this->createFilter();

    }

    public function testGetTime()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->will($this->returnValue($this->request));

        $this->request->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->response));

        $this->response->expects($this->once())
            ->method('xml')
            ->will($this->returnValue($this->responseXml));

        $response = $this->checkRestClient->getTime($this->filter);

        $this->assertEquals(true, $response);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createFilter()
    {
        return $this->getMockBuilder('Eltrino\OroCrmEbayBundle\Ebay\Filters\Filter')
            ->getMock();
    }
}
