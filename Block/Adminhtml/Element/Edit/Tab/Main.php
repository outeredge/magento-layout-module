<?php

namespace OuterEdge\Layout\Block\Adminhtml\Element\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;

class Main extends Generic
{
    /**
     * @var Config
     */
    protected $_wysiwygConfig;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        
 error_reporting(E_ALL);
ini_set('display_errors', 1);

        $element = $this->_coreRegistry->registry('element');
        
        $groupId = $this->getRequest()->getParam('group_id');

        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'enctype' => 'multipart/form-data', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Element Properties')]);
        $fieldset->addType('image', 'OuterEdge\Layout\Block\Adminhtml\Element\Helper\Image');

      /*  if ($element->getId()) {
            $fieldset->addField('element_id', 'hidden', ['name' => 'element_id']);
            $fieldset->addField('group_id', 'hidden', ['name' => 'group_id']);
        } elseif ($groupId) {
            $element->setGroupId($groupId);
            $fieldset->addField('group_id', 'hidden', ['name' => 'group_id']);
        } */

        $dataArray = [];
        foreach($element->getData() as $row) {
    
    
            switch ($row['type']) {
                case 'image':
                    $fieldset->addField(
                        $row['label'],
                        'image',
                        [
                            'name'  => $row['label'],
                            'label' => __($row['label']),
                            'title' => __($row['label']),
                            'note'  => 'Allowed types: jpg, jpeg, gif, png, svg'
                        ]
                    );
                    break;
                case 'description':
                    $fieldset->addField(
                        $row['label'],
                        'editor',
                        [
                            'name'    => $row['label'],
                            'label'   => __($row['label']),
                            'title'   => __($row['label']),
                            'wysiwyg' => true,
                            'config'  => $this->_wysiwygConfig->getConfig([
                                'hidden'        => $element->getDescription() === strip_tags($element->getDescription()),
                                'add_variables' => false,
                                'add_widgets'   => false,
                                'add_images'    => false
                            ])
                        ]
                    );
                    break;
                default:
                    $fieldset->addField(
                        $row['label'],
                        $row['type'],
                        [
                            'name'     => $row['label'],
                            'label'    => __($row['label']),
                            'title'    => __($row['label'])
                        ]
                    );
            }
             
            $dataArray[$row['label']] = $row['content'];
        }
     
        $form->setValues($dataArray);
        $this->setForm($form);

        $this->_eventManager->dispatch('element_form_build_main_tab', ['form' => $form]);

        return parent::_prepareForm();
    }
}
