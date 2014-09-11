<?php

namespace Eltrino\OroCrmEbayBundle\Writer;

use Oro\Bundle\IntegrationBundle\ImportExport\Writer\PersistentBatchWriter;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Doctrine\ORM\EntityManager;

use Oro\Bundle\ImportExportBundle\Context\ContextRegistry;
use Oro\Bundle\IntegrationBundle\Event\WriterErrorEvent;
use Oro\Bundle\IntegrationBundle\Event\WriterAfterFlushEvent;

use Akeneo\Bundle\BatchBundle\Entity\StepExecution;
use Akeneo\Bundle\BatchBundle\Item\ItemWriterInterface;
use Akeneo\Bundle\BatchBundle\Item\InvalidItemException;
use Akeneo\Bundle\BatchBundle\Step\StepExecutionAwareInterface;

class EbayBuyerWriter extends PersistentBatchWriter
{
    /**
     * {@inheritdoc}
     */
    public function write(array $items)
    {
        $this->ensureEntityManagerReady();
        $importContext = $this->contextRegistry->getByStepExecution($this->stepExecution);
        try {
            $this->em->beginTransaction();

            $uniqueTokens = array();


            foreach ($items as $item) {
                $token = $item->getEIASToken();
                if (!in_array($token, $uniqueTokens)) {
                    $uniqueTokens[] = $token;
                    $this->em->persist($item);
                    $importContext->incrementAddCount();
                }
            }

            $this->em->flush();

            $this->em->commit();
            $this->em->clear();
        } catch (\Exception $exception) {
            $this->em->rollback();

            $jobName = $this->stepExecution->getJobExecution()->getJobInstance()->getAlias();

            $event = new WriterErrorEvent($items, $jobName, $exception);
            $this->eventDispatcher->dispatch(WriterErrorEvent::NAME, $event);

            if ($event->getCouldBeSkipped()) {
                $importContext->setValue(
                    'error_entries_count',
                    (int)$importContext->getValue('error_entries_count') + count($items)
                );
                $importContext->addError($event->getWarning());

                if ($event->getException() === $exception) {
                    // exception are already handled and job can move forward
                    throw new InvalidItemException($event->getWarning(), []);
                } else {
                    // exception are already created and ready to be rethrown
                    throw $event->getException();
                }
            } else {
                throw $exception;
            }
        }

        $this->eventDispatcher->dispatch(WriterAfterFlushEvent::NAME, new WriterAfterFlushEvent($this->em));
    }
}