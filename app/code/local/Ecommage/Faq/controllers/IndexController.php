<?php

class Ecommage_Faq_IndexController extends Mage_Core_Controller_Front_Action
{

    public function preDispatch()
    {
        parent::preDispatch();

        if (!Mage::getStoreConfigFlag(Ecommage_Faq_Helper_Data::XML_PATH_ENABLED)) {
            $this->norouteAction();
        }
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function updateRateAction()
    {
        $params = $this->getRequest()->getParams();
        $rate = (int)$params['rate'];
        $faqId = (int)$params['id'];
        $returnMessage = array();

        try {
            $faq = Mage::getModel('faq/faq')->load($faqId);
            $totalRate = (int)$faq->getTotalRate() + 1;
            $helpfulRate = (int)$faq->getHelpfulRate() + $rate;
            $faq->setTotalRate($totalRate);
            $faq->setHelpfulRate($helpfulRate);
            $faq->save();
            $returnMessage['return_message'] = $helpfulRate . ' ' . $this->__('out of') . ' ' . $totalRate . ' ' . $this->__('people found this answer helpful');

        } catch (Exception $e) {
            $returnMessage['return_message'] = 'Sorry, you can not rate now';
            Mage::log($e->getMessage());
        }

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($returnMessage));
    }
}