<?php
/**
 * Created by PhpStorm.
 * User: Ruslan Voitenko
 * Date: 4/9/14
 * Time: 5:32 PM
 */

namespace Eltrino\OroCrmEbayBundle\Ebay\Filters;

class CreateTimeRangeFilter implements Filter
{
    /**
     * Date Format
     */
    const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var \DateTime
     */
    private $from;

    /**
     * @var \DateTime
     */
    private $to;

    public function __construct(\DateTime $from, \DateTime $to)
    {
        // check if range is less than 90 days, otherwise throw exception
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @param $body string
     * @return mixed
     */
    function process($body)
    {
        $requestNode = new \SimpleXMLElement($body);

        if ($requestNode->getName() != 'GetOrdersRequest') {
            throw new \RuntimeException('Given body has incorrect structure.');
        }

        $this->from->sub(new \DateInterval('PT2M'));

        $requestNode->CreateTimeFrom = $this->from->format(self::DATE_FORMAT);
        $requestNode->CreateTimeTo = $this->to->format(self::DATE_FORMAT);

        return $requestNode->asXML();
    }
}
