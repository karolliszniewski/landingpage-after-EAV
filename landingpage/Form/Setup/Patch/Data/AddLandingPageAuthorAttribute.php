<?php

namespace LandingPage\Form\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddLandingPageAuthorAttribute implements DataPatchInterface
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

        // Pobierz ID typu encji
        $entityTypeId = $this->getEntityTypeId('landingpage_form_data');

        // Dodaj atrybut 'author' bez pola 'is_visible'
        $this->moduleDataSetup->getConnection()->insert(
            $this->moduleDataSetup->getTable('eav_attribute'),
            [
                'entity_type_id' => $entityTypeId,
                'attribute_code' => 'author', // Kod atrybutu
                'backend_type' => 'varchar', // Typ danych
                'frontend_input' => 'text', // Typ pola w formularzu
                'is_required' => 0, // Atrybut nieobowiązkowy
                'is_user_defined' => 1, // Użytkownik może zdefiniować ten atrybut
                'is_unique' => 0 // Atrybut nie musi być unikalny
            ]
        );

        $this->moduleDataSetup->endSetup();
    }

    // Funkcja pobierająca ID encji na podstawie entity_type_code
    private function getEntityTypeId($entityTypeCode)
    {
        $connection = $this->moduleDataSetup->getConnection();
        $table = $this->moduleDataSetup->getTable('eav_entity_type');
        $select = $connection->select()->from($table, 'entity_type_id')->where('entity_type_code = ?', $entityTypeCode);

        return $connection->fetchOne($select);
    }

    public static function getDependencies()
    {
        return [AddLandingPageFormEntityType::class];
    }

    public function getAliases()
    {
        return [];
    }
}
