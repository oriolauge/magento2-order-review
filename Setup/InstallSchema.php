<?php
namespace OAG\OrderReview\Setup;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Install function
     * @param  SchemaSetupInterface   $setup
     * @param  ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        /**
         * Create table 'greeting_message'
         */
        $oagOrderReviewTable = $setup->getConnection()
            ->newTable($setup->getTable('oag_order_review'))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Order review ID'
            )->addColumn(
                'order_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Order ID'
            )->addColumn(
                'verified',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => false],
                'Verified'
            )->addColumn(
                'shipping',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Shipping value'
            )->addColumn(
                'product',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Product value'
            )->addColumn(
                'customer_support',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Customer support value'
            )->addColumn(
                'comment',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Comment'
            )->setComment("OAG Order review table"
            )->addForeignKey(
                $installer->getFkName(
                    'oag_order_review',
                    'order_id',
                    'sales_order',
                    'entity_id'
                ),
                'order_id',
                $installer->getTable('sales_order'), 
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->addIndex(
                $installer->getIdxName('oag_order_review', ['order_id']),
                ['order_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )->addIndex(
                $installer->getIdxName('oag_order_review', ['verified']),
                ['verified']
            );

        $setup->getConnection()->createTable($oagOrderReviewTable);

        $oagOrderReviewEmailCronjobTable = $setup->getConnection()
            ->newTable($setup->getTable('oag_order_review_email_cronjob'))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Order review ID'
            )->addColumn(
                'order_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Order ID'
            )->setComment("OAG Order review email cronjob table"
            )->addForeignKey(
                $installer->getFkName(
                    'oag_order_review_email_cronjob',
                    'order_id',
                    'sales_order',
                    'entity_id'
                ),
                'order_id',
                $installer->getTable('sales_order'), 
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->addIndex(
                $installer->getIdxName('oag_order_review_email_cronjob', ['order_id']),
                ['order_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            );

        $setup->getConnection()->createTable($oagOrderReviewEmailCronjobTable);
        $installer->endSetup();
    }
}
