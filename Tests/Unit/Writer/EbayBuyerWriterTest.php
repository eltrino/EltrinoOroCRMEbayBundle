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
namespace Eltrino\OroCrmEbayBundle\Tests\Unit\Writer;

use Akeneo\Bundle\BatchBundle\Item\ExecutionContext;
use Eltrino\OroCrmEbayBundle\Writer\EbayBuyerWriter;
use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;
use Eltrino\OroCrmEbayBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Akeneo\Bundle\BatchBundle\Entity\StepExecution;
use Oro\Bundle\ImportExportBundle\Context;
use PHPUnit\Framework\TestCase;

class EbayBuyerWriterTest extends TestCase
{
    const TEST_USERS_COUNT = 5;
    const TEST_USERS_REPETITION_COUNT = 3;

    /**
     * @var \Oro\Bundle\IntegrationBundle\ImportExport\Writer\PersistentBatchWriter
     * @Mock Oro\Bundle\IntegrationBundle\ImportExport\Writer\PersistentBatchWriter
     */
    private $persistentBatchWriter;

    /**
     * @var \Eltrino\OroCrmEbayBundle\Writer\EbayBuyerWriter
     */
    private $ebayBuyerWriter;


    public function setUp()
    {
        MockAnnotations::init($this);
    }

    /**
     * @test
     */
    public function testWrite()
    {
        $users = array();

        for ($i=0; $i<self::TEST_USERS_COUNT; $i++) {
            $users[] = new User('test_user_id_' . $i, 'test_eias_token_' . $i);
        }

        $nonUniqueUsers = $users;
        for ($j=0; $j<self::TEST_USERS_REPETITION_COUNT; $j++) {
            $nonUniqueUsers[] = $users[rand(0, self::TEST_USERS_COUNT - 1)];
        }

        $this->persistentBatchWriter
            ->expects($this->once())
            ->method('write');

        $this->ebayBuyerWriter = new EbayBuyerWriter($this->persistentBatchWriter);
        $this->ebayBuyerWriter->write($nonUniqueUsers);
    }
}
