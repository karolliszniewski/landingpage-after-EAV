<?php

namespace LandingPage\Form\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddLandingPageFormEntityType implements DataPatchInterface
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

        $this->moduleDataSetup->getConnection()->insert(
            $this->moduleDataSetup->getTable('eav_entity_type'),
            [
                'entity_type_code' => 'landingpage_form_data',
                'entity_model' => 'LandingPage\Form\Model\FormData',
                'entity_table' => 'landingpage_form',
                'entity_id_field' => 'id',
                'is_data_sharing' => 1,
                'data_sharing_key' => 'default',
                'default_attribute_set_id' => 1,
                'increment_model' => '',
                'increment_per_store' => 0,
                'increment_pad_length' => 8,
                'increment_pad_char' => '0'
            ]
        );

        $this->moduleDataSetup->endSetup();
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
