<?php

class Ecommage_Faq_Block_Widget_Grid_Colum_Renderer_Group extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {
        $faqGroupCollection = Mage::getModel('faq/faqgroup')->getCollection()
            ->addFieldToFilter('faq_group_id', array('in' => $row->getFaqGroupId()))
            ->addFieldToSelect('name');
        $faqGroupNames = array();
        foreach ($faqGroupCollection as $faqGroup) {
            $faqGroupNames[] = $faqGroup->getName();
        }
        return implode('<hr/>', $faqGroupNames);
    }

}
