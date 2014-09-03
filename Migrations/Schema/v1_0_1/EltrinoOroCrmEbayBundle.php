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
/**
 * Created by PhpStorm.
 * User: Ruslan Voitenko
 * Date: 6/5/14
 * Time: 12:16 AM
 */

namespace OroCRM\Bundle\MagentoBundle\Migrations\Schema\v1_0_1;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class EltrinoOroCrmEbayBundle implements Migration
{
    /**
     * Modifies the given schema to apply necessary changes of a database
     * The given query bag can be used to apply additional SQL queries before and after schema changes
     *
     * @param Schema $schema
     * @param QueryBag $queries
     * @return void
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Generate table eltrino_ebay_order **/
        $table = $schema->createTable('eltrino_ebay_order');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('channel_id', 'integer', ['notnull' => false]);
        $table->addColumn('user_id', 'integer', ['notnull' => false]);
        $table->addColumn('ebay_order_id', 'string', ['length' => 60]);
        $table->addColumn('buyer_user_id', 'string', ['length' => 60]);
        $table->addColumn('seller_user_id', 'string', ['length' => 60]);
        $table->addColumn('created_at', 'datetime', []);
        $table->addColumn('updated_at', 'datetime', []);
        $table->addColumn('order_status', 'string', ['length' => 60]);
        $table->addColumn('subtotal', 'float', []);
        $table->addColumn('total', 'float', []);
        $table->addColumn('seller_email', 'string', ['length' => 60]);
        $table->addColumn('amount_paid', 'float', []);
        $table->addColumn('currency_id', 'string', ['length' => 32]);
        $table->addColumn('payment_methods', 'string', ['length' => 60]);
        $table->addColumn('sales_tax_percent', 'float', []);
        $table->addColumn('sales_tax_amount', 'float', []);
        $table->addColumn('shipping_service', 'string', ['length' => 32]);
        $table->addColumn('shipping_service_cost', 'float', []);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['channel_id'], 'IDX_AE7D3C7672F5A1AA', []);
        $table->addIndex(['user_id'], 'IDX_AE7D3C76A76ED395', []);
        /** End of generate table eltrino_ebay_order **/

        /** Generate table eltrino_ebay_order_items **/
        $table = $schema->createTable('eltrino_ebay_order_items');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('order_id', 'integer', ['notnull' => false]);
        $table->addColumn('buyer_email', 'string', ['length' => 60]);
        $table->addColumn('quantity_purchased', 'integer', []);
        $table->addColumn('order_line_item_id', 'string', ['length' => 60]);
        $table->addColumn('item_id', 'string', ['length' => 60]);
        $table->addColumn('item_site', 'string', ['length' => 10]);
        $table->addColumn('item_title', 'string', ['length' => 80]);
        $table->addColumn('sku', 'string', ['length' => 60]);
        $table->addColumn('condition_display_name', 'string', ['length' => 60]);
        $table->addColumn('transaction_id', 'string', ['length' => 60]);
        $table->addColumn('transaction_price', 'float', []);
        $table->addColumn('currency_id', 'string', ['length' => 32]);
        $table->addColumn('total_tax_amount', 'float', []);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['order_id'], 'IDX_983FB6A18D9F6D38', []);
        /** End of generate table eltrino_ebay_order_items **/

        /** Generate table eltrino_ebay_user **/
        $table = $schema->createTable('eltrino_ebay_user');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('channel_id', 'integer', ['notnull' => false]);
        $table->addColumn('cotact_id', 'integer', ['notnull' => false]);
        $table->addColumn('user_id', 'string', ['length' => 255]);
        $table->addColumn('eias_token', 'string', ['length' => 255]);
        $table->addColumn('created_at', 'datetime', []);
        $table->addColumn('updated_at', 'datetime', []);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['eias_token'], 'UNIQ_79225F68188162A');
        $table->addUniqueIndex(['cotact_id'], 'UNIQ_79225F6F26C156B');
        $table->addIndex(['channel_id'], 'IDX_79225F672F5A1AA', []);
        /** End of generate table eltrino_ebay_user **/

        /** Generate table eltrino_ebay_user_address **/
        $table = $schema->createTable('eltrino_ebay_user_address');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('user_id', 'integer', ['notnull' => false]);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->addColumn('phone', 'string', ['length' => 255]);
        $table->addColumn('street1', 'string', ['length' => 255]);
        $table->addColumn('street2', 'string', ['length' => 255]);
        $table->addColumn('city', 'string', ['length' => 255]);
        $table->addColumn('state_or_province', 'string', ['length' => 255]);
        $table->addColumn('country', 'string', ['length' => 255]);
        $table->addColumn('postal_code', 'string', ['length' => 255]);
        $table->addColumn('country_name', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['user_id'], 'IDX_3F09EE52A76ED395', []);
        /** End of generate table eltrino_ebay_user_address **/

        /** Generate foreign keys for table eltrino_ebay_order **/
        $table = $schema->getTable('eltrino_ebay_order');
        $table->addForeignKeyConstraint($schema->getTable('oro_integration_channel'), ['channel_id'], ['id'], ['onDelete' => 'SET NULL', 'onUpdate' => null, ]);
        $table->addForeignKeyConstraint($schema->getTable('eltrino_ebay_user'), ['user_id'], ['id'], ['onDelete' => 'SET NULL', 'onUpdate' => null, ]);
        /** End of generate foreign keys for table eltrino_ebay_order **/

        /** Generate foreign keys for table eltrino_ebay_order_items **/
        $table = $schema->getTable('eltrino_ebay_order_items');
        $table->addForeignKeyConstraint($schema->getTable('eltrino_ebay_order'), ['order_id'], ['id'], ['onDelete' => 'CASCADE', 'onUpdate' => null, ]);
        /** End of generate foreign keys for table eltrino_ebay_order_items **/

        /** Generate foreign keys for table eltrino_ebay_user **/
        $table = $schema->getTable('eltrino_ebay_user');
        $table->addForeignKeyConstraint($schema->getTable('oro_integration_channel'), ['channel_id'], ['id'], ['onDelete' => 'SET NULL', 'onUpdate' => null, ]);
        $table->addForeignKeyConstraint($schema->getTable('orocrm_contact'), ['cotact_id'], ['id'], ['onDelete' => 'SET NULL', 'onUpdate' => null, ]);
        /** End of generate foreign keys for table eltrino_ebay_user **/

        /** Generate foreign keys for table eltrino_ebay_user_address **/
        $table = $schema->getTable('eltrino_ebay_user_address');
        $table->addForeignKeyConstraint($schema->getTable('eltrino_ebay_user'), ['user_id'], ['id'], ['onDelete' => 'CASCADE', 'onUpdate' => null, ]);
        /** End of generate foreign keys for table eltrino_ebay_user_address **/
    }
}
