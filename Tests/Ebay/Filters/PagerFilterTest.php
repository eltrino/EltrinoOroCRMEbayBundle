<?php
/**
 * Created by PhpStorm.
 * User: psw
 * Date: 4/4/14
 * Time: 5:36 PM
 */

namespace Eltrino\OroCrmEbayBundle\Tests\Ebay\Filters;Eltrino\OroCrmEbayBundle\undle\Ebay\Filters\PagerFilter;

class PagerFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Entries per page or page number has wrong values.
     */
    public function testCreateFileterWithIncorrectArguments()
    {
        new PagerFilter('value', array(1));
    }

    public function testProcess()
    {
        $entriesPerPage = 100;
        $page = 1;
        $body = "<?xml version='1.0'?><GetOrdersRequest></GetOrdersRequest>";
        $filter = new PagerFilter($entriesPerPage, $page);
        $processedBody = $filter->process($body);

        $expected = "<GetOrdersRequest><Pagination>
                        <EntriesPerPage>$entriesPerPage</EntriesPerPage>
                        <PageNumber>$page</PageNumber>
                    </Pagination></GetOrdersRequest>";

        $this->assertXmlStringEqualsXmlString($expected, $processedBody);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Given body has incorrect structure.
     */
    public function testProcessWhenIncorrectBodyGiven()
    {
        $filter = new PagerFilter(10, 1);
        $processedBody = $filter->process("<?xml version='1.0'?><AnyDummyNode></AnyDummyNode>");
    }
}
