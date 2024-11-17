<?php

namespace LandingPage\Form\Setup\Patch\Schema;

use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class CreateLandingPageFormTables implements SchemaPatchInterface
{
    private $moduleDataSetup;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        $connection = $this->moduleDataSetup->getConnection();

        // Create landingpage_form table if not exists
        if (!$connection->isTableExists($this->moduleDataSetup->getTable('landingpage_form'))) {
            $table = $connection->newTable($this->moduleDataSetup->getTable('landingpage_form'))
                ->addColumn(
                    'id',  // Primary key column
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'ID'
                )
                ->addColumn(
                    'entity_type_id',
                    Table::TYPE_SMALLINT,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Entity Type ID'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Created At'
                )
                ->addColumn(
                    'updated_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                    'Updated At'
                )
                ->addForeignKey(
                    $connection->getForeignKeyName(
                        $this->moduleDataSetup->getTable('landingpage_form'),
                        'entity_type_id',
                        'eav_entity_type',
                        'entity_type_id'
                    ),
                    'entity_type_id',
                    $this->moduleDataSetup->getTable('eav_entity_type'),
                    'entity_type_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Landing Page Form Table');
            $connection->createTable($table);
        }

        // Create varchar table for storing attributes related to landingpage_form
        if (!$connection->isTableExists($this->moduleDataSetup->getTable('landingpage_form_varchar'))) {
            $table = $connection->newTable($this->moduleDataSetup->getTable('landingpage_form_varchar'))
                ->addColumn(
                    'value_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'nullable' => false, 'primary' => true],
                    'Value ID'
                )
                ->addColumn(
                    'entity_type_id',
                    Table::TYPE_SMALLINT,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Entity Type ID'
                )
                ->addColumn(
                    'attribute_id',
                    Table::TYPE_SMALLINT,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Attribute ID'
                )
                ->addColumn(
                    'store_id',
                    Table::TYPE_SMALLINT,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'default' => 0],
                    'Store ID'
                )
                ->addColumn(
                    'id',  // Foreign key to 'landingpage_form' table
                    Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'ID'
                )
                ->addColumn(
                    'value',
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'Value'
                )
                ->addIndex(
                    $connection->getIndexName(
                        $this->moduleDataSetup->getTable('landingpage_form_varchar'),
                        ['id', 'attribute_id', 'store_id'],
                        'unique'
                    ),
                    ['id', 'attribute_id', 'store_id'],
                    ['type' => 'unique']
                )
                ->addForeignKey(
                    $connection->getForeignKeyName(
                        $this->moduleDataSetup->getTable('landingpage_form_varchar'),
                        'id',
                        'landingpage_form',
                        'id'  // Reference to 'id' column in 'landingpage_form'
                    ),
                    'id',
                    $this->moduleDataSetup->getTable('landingpage_form'),
                    'id',
                    Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $connection->getForeignKeyName(
                        $this->moduleDataSetup->getTable('landingpage_form_varchar'),
                        'entity_type_id',
                        'eav_entity_type',
                        'entity_type_id'
                    ),
                    'entity_type_id',
                    $this->moduleDataSetup->getTable('eav_entity_type'),
                    'entity_type_id',
                    Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $connection->getForeignKeyName(
                        $this->moduleDataSetup->getTable('landingpage_form_varchar'),
                        'store_id',
                        'store',
                        'store_id'
                    ),
                    'store_id',
                    $this->moduleDataSetup->getTable('store'),
                    'store_id',
                    Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $connection->getForeignKeyName(
                        $this->moduleDataSetup->getTable('landingpage_form_varchar'),
                        'attribute_id',
                        'eav_attribute',
                        'attribute_id'
                    ),
                    'attribute_id',
                    $this->moduleDataSetup->getTable('eav_attribute'),
                    'attribute_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Landing Page Form Varchar');
            $connection->createTable($table);
        }

        $this->moduleDataSetup->endSetup();
        return $this;
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}
