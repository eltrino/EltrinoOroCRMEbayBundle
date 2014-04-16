<?php

namespace OroCRM\Bundle\MagentoBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class EltrinoOroCrmEbayBundle implements Migration
{
    public function up(Schema $schema, QueryBag $queries)
    {
        $table = $schema->getTable('oro_integration_transport');
        $table->addColumn('auth_token', 'string', ['notnull' => false, 'length' => 2048]);
        $table->addColumn('dev_id', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('app_id', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('cert_id', 'string', ['notnull' => false, 'length' => 255]);
    }
}
