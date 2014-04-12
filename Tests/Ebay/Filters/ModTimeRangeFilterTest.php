<?php
/**
 * Created by PhpStorm.
 * User: psw
 * Date: 4/11/14
 * Time: 5:35 PM
 */

namespace Eltrino\EbayBundle\Tests\Ebay\Filters;

use Eltrino\EbayBundle\Ebay\Filters\ModTimeRangeFilter;

class ModTimeRangeFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        $from = new \DateTime('now', new \DateTimeZone('UTC'));
        $from->sub(new \DateInterval('P10D'));
        $to = new \DateTime('now', new \DateTimeZone('UTC'));

        $filter = new ModTimeRangeFilter($from, $to);
        $body = "<?xml version='1.0'?><GetOrdersRequest></GetOrdersRequest>";
        $processedBody = $filter->process($body);

        $expectedFrom = clone $from;
        $expectedFrom->sub(new \DateInterval('PT2M'));

        $expectedFromStr = $from->format('Y-m-d H:i:s');
        $expectedToStr = $to->format('Y-m-d H:i:s');
        $expected = "<GetOrdersRequest>
                        <ModTimeFrom>$expectedFromStr</ModTimeFrom>
                        <ModTimeTo>$expectedToStr</ModTimeTo>
                    </GetOrdersRequest>";

        $this->assertXmlStringEqualsXmlString($expected, $processedBody);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Given body has incorrect structure.
     */
    public function testProcessWhenIncorrectBodyGiven()
    {
        $filter = new ModTimeRangeFilter(new \DateTime(), new \DateTime());
        $processedBody = $filter->process("<?xml version='1.0'?><AnyDummyNode></AnyDummyNode>");
    }
}
