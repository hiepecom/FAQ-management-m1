<?php

class Ecommage_Faq_Adminhtml_FaqgroupController extends Mage_Adminhtml_Controller_action
{

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('faq/faqgroup_management')
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('FAQ Group Manager'),
                Mage::helper('adminhtml')->__('FAQ Group Manager'));

        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    /**
     * Edit FAQ group
     */
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('faq/faqgroup')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('faqgroup_data', $model);

            $this->loadLayout();
            $layout = $this->getLayout();
            $headBlock = $layout->getBlock('head');
            $this->_setActiveMenu('faq/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $headBlock->setCanLoadExtJs(true);
            if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
                $headBlock->setCanLoadTinyMce(true);
            }
            $this->_addContent($layout->createBlock('faq/adminhtml_faqgroup_edit'))
                ->_addLeft($layout->createBlock('faq/adminhtml_faqgroup_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('faq')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Save FAQ group
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('faq/faqgroup');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));

            try {

                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('faq')->__('Group was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('faq')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    /**
     * Delete FAQ group
     */
    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('faq/faqgroup');

                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * Mass Delete FAQ groups
     */
    public function massDeleteAction()
    {
        $faqGroupIds = $this->getRequest()->getParam('faq_group_id');

        if (!is_array($faqGroupIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($faqGroupIds as $faqGroupId) {
                    $faqGroup = Mage::getModel('faq/faqgroup')->load($faqGroupId);
                    $faqGroup->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($faqGroupIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass update faq group status
     */
    public function massUpdateStatusAction()
    {
        $faqGroupIds = $this->getRequest()->getParam('faq_group_id');
        if (!is_array($faqGroupIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($faqGroupIds as $faqGroupId) {
                    Mage::getModel('faq/faqgroup')
                        ->load($faqGroupId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($faqGroupIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

}