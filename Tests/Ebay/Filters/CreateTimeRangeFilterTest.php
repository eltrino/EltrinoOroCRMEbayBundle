<?php
/**
 * Created by PhpStorm.
 * User: psw
 * Date: 4/11/14
 * Time: 5:32 PM
 */

namespace Eltrino\OroCrmEbayBundle\Tests\Ebay\Filters;Eltrino\OroCrmEbayBundle\undle\Ebay\Filters\CreateTimeRangeFilter;

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
        $processedBody = $filter->process("<?xml version='1.0'?><AnyDummyNode></AnyDummyNode>");
    }
}
