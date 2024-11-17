<?php

/**
 * Webkul_Grid Add New Row Form Admin Block.
 * @category    Webkul
 * @package     Webkul_Grid
 * @author      Webkul Software Private Limited
 *
 */

namespace LandingPage\Form\Block\Adminhtml\Grid\Edit;

use Magento\User\Model\ResourceModel\User\CollectionFactory;

/**
 * Adminhtml Add New Row Form.
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var CollectionFactory
     */
    private $userCollectionFactory;


    /**
     * @param \Magento\Backend\Block\Template\Context $context,
     * @param \Magento\Framework\Registry $registry,
     * @param \Magento\Framework\Data\FormFactory $formFactory,
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
     * @param \Webkul\Grid\Model\Status $options,
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Webkul\Grid\Model\Status $options,
        CollectionFactory $userCollectionFactory,

        array $data = []
    ) {
        $this->_options = $options;
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->userCollectionFactory = $userCollectionFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $model = $this->_coreRegistry->registry('row_data');
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'enctype' => 'multipart/form-data',
                    'action' => $this->getData('action'),
                    'method' => 'post'
                ]
            ]
        );

        $form->setHtmlIdPrefix('wkgrid_');
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Edit Row Data'), 'class' => 'fieldset-wide']);

        $fieldset->addField(
            'id',
            'text',
            [
                'name' => 'id',
                'label' => __('Post Id'),
                'id' => 'id',
                'title' => __('Post Id'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        // Add dropdown field for customer_id
        $fieldset->addField(
            'customer_id',
            'select',
            [
                'name' => 'customer_id',
                'label' => __('Customer Id'),
                'id' => 'customer_id',
                'title' => __('Customer id'),
                'values' => [
                    ['value' => 1, 'label' => __('Customer 1')], // Option 1
                    ['value' => 2, 'label' => __('Customer 2')], // Option 2
                ],
                'value' => 2, // Default selected value
                'class' => 'required-entry',
                'required' => true,
            ]
        );


        $fieldset->addField(
            'comment',
            'text',
            [
                'name' => 'comment',
                'label' => __('Comment'),
                'id' => 'comment',
                'title' => __('Comment'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );


        $fieldset->addField(
            'author',
            'text',
            [
                'name' => 'author',
                'label' => __('Author'),
                'id' => 'author',
                'title' => __('Author'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );



        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }


    private function getUserOptions()
    {
        $options = [];
        $userCollection = $this->userCollectionFactory->create();

        foreach ($userCollection as $user) {
            $options[] = [
                'value' => $user->getId(),
                'label' => $user->getFirstname() . ' ' . $user->getLastname(),
            ];
        }

        return $options;
    }
}
