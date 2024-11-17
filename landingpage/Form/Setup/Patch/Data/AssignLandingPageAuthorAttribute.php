<?php

namespace LandingPage\Form\Setup\Patch\Data;

use Magento\Eav\Api\AttributeSetManagementInterface;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\Entity\Attribute\SetFactory;
use Magento\Eav\Model\Entity\Attribute as EavAttribute;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Api\AttributeSetRepositoryInterface;

class AssignLandingPageAuthorAttribute implements DataPatchInterface
{
    private $moduleDataSetup;
    private $eavConfig;
    private $attributeSetFactory;
    private $eavAttribute;
    private $attributeSetManagement;
    private $attributeSetRepository;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        Config $eavConfig,
        SetFactory $attributeSetFactory,
        EavAttribute $eavAttribute,
        AttributeSetManagementInterface $attributeSetManagement,
        AttributeSetRepositoryInterface $attributeSetRepository
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavConfig = $eavConfig;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->eavAttribute = $eavAttribute;
        $this->attributeSetManagement = $attributeSetManagement;
        $this->attributeSetRepository = $attributeSetRepository;
    }

    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        try {
            // Get entity type ID
            $entityTypeId = $this->getEntityTypeId('landingpage_form_data');

            // Get the author attribute
            $attribute = $this->eavConfig->getAttribute('landingpage_form_data', 'author');

            if ($attribute && $attribute->getId()) {
                // Get attribute set
                $attributeSetId = 1; // Adjust this ID to match your attribute set
                $attributeGroupId = 1; // Adjust this ID to match your attribute group

                // Load the attribute set using repository
                $attributeSet = $this->attributeSetRepository->get($attributeSetId);

                if ($attributeSet && $attributeSet->getId()) {
                    // Assign the attribute to the set
                    $this->attributeSetManagement->assign(
                        $entityTypeId,
                        $attributeSetId,
                        $attributeGroupId,
                        $attribute->getAttributeId(),
                        999 // sort_order
                    );
                }
            }
        } catch (\Exception $e) {
            throw new \Exception('Error assigning attribute: ' . $e->getMessage());
        }

        $this->moduleDataSetup->endSetup();

        return $this;
    }

    private function getEntityTypeId($entityTypeCode)
    {
        $connection = $this->moduleDataSetup->getConnection();
        $table = $this->moduleDataSetup->getTable('eav_entity_type');
        $select = $connection->select()
            ->from($table, 'entity_type_id')
            ->where('entity_type_code = ?', $entityTypeCode);

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
