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

class PagerFilter implements Filter
{
    private $entriesPerPage;
    private $page;

    public function __construct($entriesPerPage, $page)
    {
        $this->entriesPerPage = intval($entriesPerPage);
        $this->page = intval($page);

        if ($this->entriesPerPage <= 0 || $this->page <= 0) {
            throw new \InvalidArgumentException('Entries per page or page number has wrong values.');
        }
    }

    /**
     * @param $body string
     * @return mixed
     */
    function process($body)
    {
        $getOrdersRequestNode = new \SimpleXMLElement($body);

        if ($getOrdersRequestNode->getName() != 'GetOrdersRequest') {
            throw new \RuntimeException('Given body has incorrect structure.');
        }

        if (!$getOrdersRequestNode->Pagination) {
            $getOrdersRequestNode->addChild('Pagination');
        }

        /** @var \SimpleXMLElement $pagination */
        $pagination = $getOrdersRequestNode->Pagination;

        if (!$pagination->EntriesPerPage) {
            $pagination->addChild('EntriesPerPage');
        }
        $pagination->EntriesPerPage = $this->entriesPerPage;

        if (!$pagination->PageNumber) {
            $pagination->addChild('PageNumber');
        }
        $pagination->PageNumber = $this->page;

        return $getOrdersRequestNode->asXML();
    }
}
