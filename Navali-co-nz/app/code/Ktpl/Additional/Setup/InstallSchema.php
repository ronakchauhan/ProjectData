<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ktpl\Additional\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Quote\Setup\QuoteSetupFactory;
use Magento\Sales\Setup\SalesSetupFactory;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    public function __construct(
            SalesSetupFactory $salesSetupFactory,
            QuoteSetupFactory $quoteSetupFactory
        )
    {
        $this->salesSetupFactory = $salesSetupFactory;
        $this->quoteSetupFactory = $quoteSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $setup->getConnection()->addColumn(
                $setup->getTable('sales_order_address'), 'suburb', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'unsigned' => true,
            'nullable' => true,
            'comment' => 'Set Suburb'                ]
        );

        $setup->getConnection()->addColumn(
                $setup->getTable('quote_address'), 'suburb', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'unsigned' => true,
            'nullable' => true,
            'comment' => 'Set Suburb'                ]
        );
        // $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);
        // $salesSetup->addAttribute('order_address', 'suburb', ['type' => 'text']);
        // $quoteSetup = $this->quoteSetupFactory->create(['setup' => $setup]);
        // $quoteSetup->addAttribute('quote_address', 'suburb', ['type' => 'text']);

        $installer->endSetup();

    }
}
