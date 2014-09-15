<?php

namespace Eltrino\OroCrmEbayBundle\Writer;

use Oro\Bundle\IntegrationBundle\ImportExport\Writer\PersistentBatchWriter;
use Oro\Bundle\IntegrationBundle\Event\WriterErrorEvent;
use Oro\Bundle\IntegrationBundle\Event\WriterAfterFlushEvent;

use Akeneo\Bundle\BatchBundle\Item\InvalidItemException;

class EbayBuyerWriter extends PersistentBatchWriter
{
    /**
     * {@inheritdoc}
     */
    public function write(array $items)
    {
        $uniqueTokens = $uniqueItems = array();

        foreach ($items as $item) {
            $token = $item->getEIASToken();
            if (!in_array($token, $uniqueTokens)) {
                $uniqueTokens[] = $token;
                $uniqueItems[]  = $item;
            }
        }

        parent::write($uniqueItems);
    }
}