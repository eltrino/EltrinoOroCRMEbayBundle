<?php
/**
 * Created by PhpStorm.
 * User: vuki
 * Date: 3/31/14
 * Time: 2:31 PM
 */

namespace Eltrino\EbayBundle\Ebay\Filters;

class CompositeFilter implements Filter
{
    /**
     * @var array
     */
    private $filters = array();

    /**
     * @param string $body
     * @return string
     */
    public function process($body)
    {
        foreach ($this->filters as $filter) {
            $body = $filter->process($body);
        }

        return $body;
    }

    /**
     * @param Filter $filter
     */
    public function addFilter(Filter $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * @return void
     */
    public function reset()
    {
        $this->filters = array();
    }
}
