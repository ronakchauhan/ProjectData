<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket Private Sales and Flash Sales v4.x.x
 * @copyright   Copyright (c) 2016 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

namespace Plumrocket\PrivateSale\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{


    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();


        $table = $installer->getConnection()
            ->newTable($installer->getTable('plumrocket_privatesale_product_indexer'))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )
            ->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false],
                'Product Id'
            )
            ->addColumn(
                'category_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false],
                'Category Id'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                0,
                [],
                'Store Id'
            )
            ->addColumn(
                'start_date',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                [],
                'Start Date'
            )
            ->addColumn(
                'end_date',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                [],
                'End date'
            )
            ->addColumn(
                'event_start_action',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                [],
                'Event Start Action'
            )
            ->addColumn(
                'event_start_action_use_config',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                [],
                'Event Start Action Use Config'
            )
            ->addColumn(
                'event_end_action',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                [],
                'Event End Action'
            )
            ->addColumn(
                'event_end_action_use_config',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                [],
                'Event End Action Use Config'
            )
            ->setComment('Plumrocket PrivateSale Event Status');
        $installer->getConnection()->createTable($table);

		$table = $installer->getConnection()
            ->newTable($installer->getTable('plumrocket_privatesale_preview_access'))
            ->addColumn(
                'code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Code'
            )
            ->addColumn(
                'active_to',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                [],
                'Active To'
            )
            ->setComment('Plumrocket Private Sales Preview Access Codes');
        $installer->getConnection()->createTable($table);


        $table = $installer->getConnection()
            ->newTable($installer->getTable('plumrocket_privatesale_image'))
            ->addColumn(
                'image_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Image Id'
            )
            ->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Name of image'
            )
            ->addColumn(
                'active_from',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => true],
                'Active from'
            )
            ->addColumn(
                'active_to',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => true],
                'Active To'
            )
            ->addColumn(
                'sort_order',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['default' => 0, 'nullable' => false],
                'Sort order'
            )
            ->addColumn(
                'exclude',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['default' => 0, 'nullable' => false],
                'Exclude'
            )
            ->setComment('Plumrocket PrivateSale Images');
        $installer->getConnection()->createTable($table);

		$table = $installer->getConnection()
            ->newTable($installer->getTable('plumrocket_privatesale_emailtemplates'))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )
            ->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Name'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [],
                'Store Id'
            )
            ->addColumn(
                'date',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                [],
                'Date'
            )
            ->addColumn(
                'categories_ids',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Categories Ids'
            )
            ->addColumn(
                'title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Title'
            )
            ->addColumn(
                'period_text',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Period Text'
            )
            ->addColumn(
                'template',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Template'
            )
            ->addColumn(
                'template_date_format',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Template Date Format'
            )
            ->addColumn(
                'list_layout',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                'List Layout'
            )
            ->addColumn(
                'list_template',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'List Template'
            )
            ->addColumn(
                'list_template_date_format',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'list_template_date_format'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                [],
                'created_at'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                [],
                'updated_at'
            )
            ->setComment('Plumrocket Private Sales Preview Access Codes');
        $installer->getConnection()->createTable($table);


        $installer->endSetup();

    }
}
