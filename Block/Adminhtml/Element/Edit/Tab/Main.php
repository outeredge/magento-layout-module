<?php

namespace OuterEdge\Layout\Block\Adminhtml\Element\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
use OuterEdge\Layout\Helper\Data as TemplatesHelper;
use OuterEdge\Layout\Block\Adminhtml\Element\Helper\CategoryMultiselect;

class Main extends Generic
{
    protected $_templates;

    /**
     * @var Config
     */
    protected $_wysiwygConfig;

    /**
     * @var CategoryMultiselect
     */
    protected $categoryMultiselect;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param TemplatesHelper $templates
     * @param CategoryMultiselect $categoryMultiselect
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        TemplatesHelper $templates,
        CategoryMultiselect $categoryMultiselect,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_templates = $templates;
        $this->categoryMultiselect = $categoryMultiselect;
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
        $element = $this->_coreRegistry->registry('elementModel');
        $groupId = $this->getRequest()->getParam('group_id');

        $form = $this->_formFactory->create(
            ['data' => [
                'id' => 'edit_form',
                'enctype' => 'multipart/form-data',
                'action' => $this->getData('action'),
                'method' => 'post']]
        );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Element Properties')]);
        $fieldset->addType('image', 'OuterEdge\Layout\Block\Adminhtml\Element\Helper\Image');

        //Category chooser not is use, because issue on css/js
        //$fieldset->addType('category', 'OuterEdge\Layout\Block\Adminhtml\Element\Helper\CategoryChooser');

        if ($element->getId()) {
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
            $fieldset->addField('group_id', 'hidden', ['name' => 'group_id']);
        } elseif ($groupId) {
            $element->setGroupId($groupId);
            $fieldset->addField('group_id', 'hidden', ['name' => 'group_id']);
        }

        //Ask factory template for group code name
        $templateData = $this->_templates->getFieldsTemplate($groupId);

        //Fixed fields
        $fieldset->addField(
            'title',
            'text',
            [
                'name'  => 'title',
                'label' => __('Title'),
                'title' => __('Title')
            ]
        );

        $count = 0;
        //Dynamic fields
        foreach ($templateData as $key => $field) {
            $label = ucfirst(str_replace("_", " ", key($field)));
            $identifier = $key;
            $type = reset($field);

            switch ($type) {
                case 'image':
                    $fieldset->addField(
                        $identifier,
                        'image',
                        [
                            'name'  => $identifier,
                            'label' => __($label),
                            'title' => __($label),
                            'note'  => 'Allowed types: jpg, jpeg, gif, png, svg'
                        ]
                    );
                    $count++;
                    $fieldset->addField("image_identifier[$count]", 'hidden', ['name' => "image_identifier[$count]"]);
                    $element->setData("image_identifier[$count]", $identifier);
                    break;
                case 'editor':
                    $fieldset->addField(
                        $identifier,
                        'editor',
                        [
                            'name'    => $identifier,
                            'label'   => __($label),
                            'title'   => __($label),
                            'wysiwyg' => true,
                            'config'  => $this->_wysiwygConfig->getConfig([
                                'hidden'        => $element->getDescription() === strip_tags((string) $element->getDescription()),
                                'add_variables' => false,
                                'add_widgets'   => false,
                                'add_images'    => false
                            ])
                        ]
                    );
                    break;
                case 'date':
                    $fieldset->addField(
                        $identifier,
                        'date',
                        [
                            'name' => $identifier,
                            'label' => __($label),
                            'date_format' => 'yyyy-MM-dd',
                            'time_format' => 'HH:mm:ss'
                        ]
                    );
                    break;
                case 'position':
                    $fieldset->addField(
                        $identifier,
                        'select',
                        [
                            'name' => $identifier,
                            'label' => __($label),
                            'values'   => [
                                'right'        => 'Right',
                                'left'         => 'Left',
                                'center'       => 'Center',
                                'top-right'    => 'Top Right',
                                'top-left'     => 'Top Left',
                                'top-center'   => 'Top Center',
                                'bottom-right' => 'Bottom Right',
                                'bottom-left'  => 'Bottom Left',
                                'bottom-center'=> 'Bottom Center'
                             ],
                        ]
                    );
                    break;
		        case 'boolean':
                    $fieldset->addField(
                        $identifier,
                        'select',
                        [
                            'name' => $identifier,
                            'label' => __($label),
                            'values' => [
                                '1' => 'Yes',
                                '0' => 'No'
                             ],
                        ]
                    );
                    break;
                case 'categories':
                    $fieldset->addField(
                        $identifier,
                        'multiselect', //'category'
                        [
                            'name' => $identifier,
                            'label' => __($label),
                            'values' => $this->categoryMultiselect->toOptionArray()
                        ]
                    );
                    $count++;
                    $fieldset->addField("category_identifier[$count]", 'hidden', ['name' => "category_identifier[$count]"]);
                    $element->setData("category_identifier[$count]", $identifier);
                    break;
                default:
                    $fieldset->addField(
                        $identifier,
                        $type,
                        [
                            'name'     => $identifier,
                            'label'    => __($label),
                            'title'    => __($label)
                        ]
                    );
            }
        }

        $fieldset->addField(
            'sort_order',
            'text',
            [
                'name'  => 'sort_order',
                'label' => __('Sort Order'),
                'title' => __('Sort Order')
            ]
        );

        $this->_eventManager->dispatch('element_form_build_main_tab', ['form' => $form]);

        $form->setValues($element->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
