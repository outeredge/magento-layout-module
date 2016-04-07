<?php
class Edge_Layout_Adminhtml_GroupsController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('cms/layout');
    }

    protected function _initAction()
    {
        $this->loadLayout()
            ->_title($this->__('Layout'))
            ->_title($this->__('Groups'))
            ->_setActiveMenu('layoutelements');
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()
             ->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->_title($this->__('Layout'))
             ->_title($this->__('Items'))
             ->_title($this->__('Manage Content'));
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('group_id');
        $model = Mage::getModel('layout/layout_groups');

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (! $model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('layout')->__('This item no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        $this->_title($model->getId() ? $model->getTitle() : $this->__('New Item'));
        // 3. Set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (! empty($data)) {
            $model->setData($data);
        }
        // 4. Register model to use later in blocks
        Mage::register('layout_group', $model);
        // 5. Build edit form
        $this->_initAction()
             ->_addBreadcrumb(
                $id ? Mage::helper('layout')->__('Edit Item')
                    : Mage::helper('layout')->__('New Item'),
                $id ? Mage::helper('layout')->__('Edit Item')
                    : Mage::helper('layout')->__('New Item'));
        $this->renderLayout();
    }

    public function saveAction()
    {
        // check if data sent
        if ($data = $this->getRequest()->getPost()) {

            //init model and set data
            $model = Mage::getModel('layout/layout_groups');
            $model->setData($data);
            // try to save it
            try {
                // save the data
                $model->save();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('layout')->__('The item has been saved.'));
                // clear previously saved data from session
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('group_id' => $model->getId()));
                    return;
                }
                // go to grid
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
            catch (Exception $e) {
                $this->_getSession()->addException($e,
                    Mage::helper('layout')->__('An error occurred while saving the item.'));
            }
            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('group_id' => $this->getRequest()->getParam('group_id')));
            return;
        }
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        // check if we know what should be deleted
        if ($id = $this->getRequest()->getParam('group_id')) {
            try {
                // init model and delete
                $model = Mage::getModel('layout/layout_groups');
                $model->load($id);
                $model->delete();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('layout')->__('The item has been deleted.'));
                // go to grid
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // go back to edit form
                $this->_redirect('*/*/edit', array('group_id' => $id));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('layout')->__('Unable to find a item to delete.'));
        // go to grid
        $this->_redirect('*/*/');
    }
}