<?php

namespace Eltrino\OroCrmEbayBundle\Provider;

use Oro\Bundle\IntegrationBundle\Provider\ConnectorInterface;

class EbayCustomerConnector implements ConnectorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'Customer connector';
    }

    /**
     * Return source iterator to read from
     *
     * @return \Iterator
     */
    protected function getConnectorSource()
    {
        // TODO: Implement getConnectorSource() method.
    }

    /**
     * Returns entity name that will be used for matching "import processor"
     *
     * @return string
     */
    public function getImportEntityFQCN()
    {
        // TODO: Implement getImportEntityFQCN() method.
    }

    /**
     * Returns job name for import
     *
     * @return string
     */
    public function getImportJobName()
    {
        return 'ebay_customer_import';
    }

    /**
     * Returns type name, the same as registered in service tag
     *
     * @return string
     */
    public function getType()
    {
        return 'customer';
    }
}
