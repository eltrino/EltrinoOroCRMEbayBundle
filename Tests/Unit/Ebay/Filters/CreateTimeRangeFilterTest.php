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
namespace Eltrino\OroCrmEbayBundle\Tests\Unit\Ebay\Filters;

use Eltrino\OroCrmEbayBundle\Ebay\Filters\CreateTimeRangeFilter;

class CreateTimeRangeFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        $from = new \DateTime('now', new \DateTimeZone('UTC'));
        $from->sub(new \DateInterval('P10D'));
        $to = new \DateTime('now', new \DateTimeZone('UTC'));

        $filter = new CreateTimeRangeFilter($from, $to);
        $body = "<?xml version='1.0'?><GetOrdersRequest></GetOrdersRequest>";
        $processedBody = $filter->process($body);

        $expectedFrom = clone $from;
        $expectedFrom->sub(new \DateInterval('PT2M'));

        $expectedFromStr = $from->format('Y-m-d H:i:s');
        $expectedToStr = $to->format('Y-m-d H:i:s');
        $expected = "<GetOrdersRequest>
                        <CreateTimeFrom>$expectedFromStr</CreateTimeFrom>
                        <CreateTimeTo>$expectedToStr</CreateTimeTo>
                    </GetOrdersRequest>";

        $this->assertXmlStringEqualsXmlString($expected, $processedBody);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Given body has incorrect structure.
     */
    public function testProcessWhenIncorrectBodyGiven()
    {
        $filter = new CreateTimeRangeFilter(new \DateTime(), new \DateTime());
        $filter->process("<?xml version='1.0'?><AnyDummyNode></AnyDummyNode>");
    }
}
