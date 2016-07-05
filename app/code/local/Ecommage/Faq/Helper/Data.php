<?php

class Ecommage_Faq_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_CONFIG_HELPFUL = 'faq/faq/enabled_helpful';
    const XML_PATH_ENABLED = 'faq/faq/enabled';

    public function setWysiwygConfig()
    {
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
        $wysiwygConfig->addData(array(
            'add_variables' => false,
            'plugins' => array(),
            'widget_window_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/widget/index'),
            'directives_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive'),
            'directives_url_quoted' => preg_quote(Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive')),
            'files_browser_window_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index')
        ));
        return $wysiwygConfig;
    }

    public function getGroups()
    {
        $groupCollection = Mage::getModel('faq/faqgroup')->getCollection();
        $groupArray = array();
        $count = 0;

        foreach ($groupCollection as $group) {
            $groupArray[$count++] = array(
                'value' => $group->getFaqGroupId(),
                'label' => $group->getName()
            );
        }
        return $groupArray;
    }

    public function getGroupFiltersGrid()
    {
        $groupCollection = Mage::getModel('faq/faqgroup')->getCollection();
        $groupArray = array();

        foreach ($groupCollection as $group) {
            $groupArray[$group->getFaqGroupId()] = $group->getName();
        }
        return $groupArray;
    }

    public function isHelpfulEnabled()
    {
        $helpfulConfig = (int)Mage::getStoreConfig(self::XML_PATH_CONFIG_HELPFUL);
        return $helpfulConfig == 1 ? true : false;
    }
}