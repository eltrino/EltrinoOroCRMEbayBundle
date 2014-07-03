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
namespace Eltrino\OroCrmEbayBundle\Ebay\Filters;

class ModTimeRangeFilter implements Filter
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
        // check if range is less than 30 days, otherwise throw exception
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @param $body string
     * @return mixed
     */
    public function process($body)
    {
        $requestNode = new \SimpleXMLElement($body);

        if ($requestNode->getName() != 'GetOrdersRequest') {
            throw new \RuntimeException('Given body has incorrect structure.');
        }

        $this->from->sub(new \DateInterval('PT2M'));

        $requestNode->ModTimeFrom = $this->from->format(self::DATE_FORMAT);
        $requestNode->ModTimeTo = $this->to->format(self::DATE_FORMAT);

        return $requestNode->asXML();
    }
}
