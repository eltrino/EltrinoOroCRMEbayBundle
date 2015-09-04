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

use Eltrino\OroCrmEbayBundle\Ebay\Filters\PagerFilter;

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
        $filter->process("<?xml version='1.0'?><AnyDummyNode></AnyDummyNode>");
    }
}
