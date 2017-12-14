<?php

namespace Ktpl\Blog\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Blog schema update
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $setup->startSetup();

        $version = $context->getVersion();
        $connection = $setup->getConnection();

        if (version_compare($version, '2.0.1') < 0) {

            foreach (['ktpl_blog_post_relatedpost', 'ktpl_blog_post_relatedproduct'] as $tableName) {
                // Get module table
                $tableName = $setup->getTable($tableName);

                // Check if the table already exists
                if ($connection->isTableExists($tableName) == true) {

                    $columns = [
                        'position' => [
                            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                            'nullable' => false,
                            'comment' => 'Position',
                        ],
                    ];

                    foreach ($columns as $name => $definition) {
                        $connection->addColumn($tableName, $name, $definition);
                    }

                }
            }

            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'featured_img',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Thumbnail Image',
                ]
            );
        }

        if (version_compare($version, '2.2.0') < 0) {
            /* Add author field to posts tabel */
            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'author_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'nullable' => true,
                    'comment' => 'Author ID',

                ]
            );

            $connection->addIndex(
                $setup->getTable('ktpl_blog_post'),
                $setup->getIdxName($setup->getTable('ktpl_blog_post'), ['author_id']),
                ['author_id']
            );

        }


        if (version_compare($version, '2.2.5') < 0) {
            /* Add layout field to posts and category tabels */
            foreach (['ktpl_blog_post', 'ktpl_blog_category'] as $table) {
                $table = $setup->getTable($table);
                $connection->addColumn(
                    $setup->getTable($table),
                    'page_layout',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'nullable' => true,
                        'comment' => 'Post Layout',
                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'layout_update_xml',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => '64k',
                        'nullable' => true,
                        'comment' => 'Post Layout Update Content',
                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'custom_theme',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 100,
                        'nullable' => true,
                        'comment' => 'Post Custom Theme',
                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'custom_layout',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'nullable' => true,
                        'comment' => 'Post Custom Template',
                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'custom_layout_update_xml',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => '64k',
                        'nullable' => true,
                        'comment' => 'Post Custom Layout Update Content',
                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'custom_theme_from',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                        'nullable' => true,
                        'comment' => 'Post Custom Theme Active From Date',
                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'custom_theme_to',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                        'nullable' => true,
                        'comment' => 'Post Custom Theme Active To Date',
                    ]
                );
            }

        }

        if (version_compare($version, '2.3.0') < 0) {
            /* Add meta title field to posts tabel */
            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'meta_title',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Post Meta Title',
                    'after' => 'title'
                ]
            );

            /* Add og tags fields to post tabel */
            foreach (['type', 'img', 'description', 'title'] as $type) {
                $connection->addColumn(
                    $setup->getTable('ktpl_blog_post'),
                    'og_' . $type,
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'nullable' => true,
                        'comment' => 'Post OG ' . ucfirst($type),
                        'after' => 'identifier'
                    ]
                );
            }

            /* Add meta title field to category tabel */
            $connection->addColumn(
                $setup->getTable('ktpl_blog_category'),
                'meta_title',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Category Meta Title',
                    'after' => 'title'
                ]
            );

            /**
             * Create table 'ktpl_blog_tag'
             */
            $table = $setup->getConnection()->newTable(
                $setup->getTable('ktpl_blog_tag')
            )->addColumn(
                'tag_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Tag ID'
            )->addColumn(
                'title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Tag Title'
            )->addColumn(
                'identifier',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                100,
                ['nullable' => true, 'default' => null],
                'Tag String Identifier'
            )->addIndex(
                $setup->getIdxName('ktpl_blog_tag', ['identifier']),
                ['identifier']
            )->setComment(
                'Ktpl Blog Tag Table'
            );
            $setup->getConnection()->createTable($table);

            /**
             * Create table 'ktpl_blog_post_tag'
             */
            $table = $setup->getConnection()->newTable(
                $setup->getTable('ktpl_blog_post_tag')
            )->addColumn(
                'post_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'primary' => true],
                'Post ID'
            )->addColumn(
                'tag_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'primary' => true],
                'Tag ID'
            )->addIndex(
                $setup->getIdxName('ktpl_blog_post_tag', ['tag_id']),
                ['tag_id']
            )->addForeignKey(
                $setup->getFkName('ktpl_blog_post_tag', 'post_id', 'ktpl_blog_post', 'post_id'),
                'post_id',
                $setup->getTable('ktpl_blog_post'),
                'post_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->addForeignKey(
                $setup->getFkName('ktpl_blog_post_tag', 'tag_id', 'ktpl_blog_tag', 'tag_id'),
                'tag_id',
                $setup->getTable('ktpl_blog_tag'),
                'tag_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment(
                'Ktpl Blog Post To Category Linkage Table'
            );
            $setup->getConnection()->createTable($table);
        }

        if (version_compare($version, '2.4.4') < 0) {
            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'media_gallery',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '2M',
                    'nullable' => true,
                    'comment' => 'Media Gallery',
                ]
            );
        }

        if (version_compare($version, '2.6.0') < 0) {
            $connection->addColumn(
                $setup->getTable('ktpl_blog_category'),
                'block_layout',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '255',
                    'nullable' => true,
                    'comment' => 'Category Section',
                ]
            );
            $connection->addColumn(
                $setup->getTable('ktpl_blog_category'),
                'filter_status',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'length' => '6',
                    'nullable' => false,
                    'default'=>'0',
                    'comment' => 'Category Filter Status',
                ]
            );

            /**
             * Create table 'ktpl_blog_tag_type'
             */
            $table = $setup->getConnection()->newTable(
                $setup->getTable('ktpl_blog_tag_type')
            )->addColumn(
                'tag_type_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Tag Type ID'
            )->addColumn(
                'title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Tag Type Title'
            )->addColumn(
                'identifier',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                100,
                ['nullable' => true, 'default' => null],
                'Tag Type String Identifier'
            )->addIndex(
                $setup->getIdxName('ktpl_blog_tag_type', ['identifier']),
                ['identifier']
            )->setComment(
                'Ktpl Blog Tag Type Table'
            );

            $connection->addColumn(
                $setup->getTable('ktpl_blog_tag'),
                'tag_type_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'length' => '6',
                    'nullable' => false,
                    'default'=>'0',
                    'comment' => 'Tag Type Link Id',
                ]
            );
            
            $setup->getConnection()->createTable($table);
        }
        if (version_compare($version, '2.6.1') < 0) {
            $connection->addColumn(
                $setup->getTable('ktpl_blog_tag_type'),
                'visibility',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'length' => '6',
                    'nullable' => false,
                    'default'=>'0',
                    'comment' => 'Tag Type in Categories',
                ]
            );
            $connection->addColumn(
                $setup->getTable('ktpl_blog_tag_type'),
                'visible_category',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '255',
                    'nullable' => true,
                    'comment' => 'Visible in Categories',
                ]
            );
            $connection->addColumn(
                $setup->getTable('ktpl_blog_tag_type'),
                'hide_category',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '255',
                    'nullable' => true,
                    'comment' => 'Hide in Categories',
                ]
            );

            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'is_favourite',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'length' => 6,
                    'nullable' => false,
                    'default' => '0',
                    'comment' => 'Post Meta Title',
                    'after' => 'title'
                ]
            );

            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'post_text',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Post Text',
                    'after' => 'media_gallery'
                ]
            );

            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'custom_class',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Custom Class for frontend',
                    'after' => 'post_text'
                ]
            );

            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'custom_css',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Custom CSS for frontend',
                    'after' => 'custom_class'
                ]
            );

            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'show_explore_button',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'length' => 6,
                    'nullable' => false,
                    'default' => '0',
                    'comment' => 'Post Show Explore Button',
                    'after' => 'custom_css'
                ]
            );

            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'explore_button_text',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => false,
                    'default' => 'Explore Blog',
                    'comment' => 'Post Explore Button Text',
                    'after' => 'show_explore_button'
                ]
            );

            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'view_post_layout',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Layout Setting'
                ]
            );

            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'short_desc',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '64k',
                    'nullable' => true,
                    'comment' => 'Post Short description'
                ]
            );

            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'short_desc_title',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Post Short description Title'
                ]
            );

            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'left_desc_title',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Post Short description Title'
                ]
            );

            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'left_desc',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '64k',
                    'nullable' => true,
                    'comment' => 'Post Left description'
                ]
            );

            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'left_desc_title',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Post Short description Title'
                ]
            );

            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'right_desc',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '64k',
                    'nullable' => true,
                    'comment' => 'Post Right description'
                ]
            );

            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'article_link',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Post Article Link'
                ]
            );

            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'verticle_desc_one_title',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Post Short description Title'
                ]
            );

            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'verticle_desc_one',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '64k',
                    'nullable' => true,
                    'comment' => 'Post Verticle description one'
                ]
            );

            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'verticle_desc_two_title',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Post Short description Title'
                ]
            );

            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'verticle_desc_two',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '64k',
                    'nullable' => true,
                    'comment' => 'Post Verticle description two'
                ]
            );

            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'verticle_desc_three_title',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Post Short description Title'
                ]
            );
            
            $connection->addColumn(
                $setup->getTable('ktpl_blog_post'),
                'verticle_desc_three',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '64k',
                    'nullable' => true,
                    'comment' => 'Post Verticle description three'
                ]
            );
        }

         if (version_compare($version, '2.6.2') < 0) {
            /**
             * Create table 'ktpl_blog_post_tag'
             */
            $table = $setup->getConnection()->newTable(
                $setup->getTable('ktpl_blog_post_wishlist')
            )->addColumn(
                'wishlist_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [ 'identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Post Wishlist ID'
            )->addColumn(
                'post_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'primary' => false],
                'Post ID'
            )->addColumn(
                'customer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'primary' => false],
                'Customer ID'
            )->addColumn(
                'update_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Post Wishlist Modification Time'
            )->addIndex(
                $setup->getIdxName('ktpl_blog_post_wishlist', ['wishlist_id']),
                ['wishlist_id']
            )->addForeignKey(
                $setup->getFkName('ktpl_blog_post_wishlist', 'post_id', 'ktpl_blog_post', 'post_id'),
                'post_id',
                $setup->getTable('ktpl_blog_post'),
                'post_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment(
                'Ktpl Blog Post Wishlist Table'
            );
            $setup->getConnection()->createTable($table);
        }
            
        $setup->endSetup();
    }
}
