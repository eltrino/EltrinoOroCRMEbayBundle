<?php
/**
 * Created by PhpStorm.
 * User: Ruslan Voitenko
 * Date: 4/4/14
 * Time: 5:29 PM
 */

namespace Eltrino\EbayBundle\Ebay\Filters;

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
