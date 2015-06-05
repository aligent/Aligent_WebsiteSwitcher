<?php
/**
 * @category    Aligent
 * @package     Aligent_WebsiteSwitcher
 * @copyright   Copyright (c) 2013 Aligent Consulting. (http://www.aligent.com.au)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * @author 		Luke Mills <luke@aligent.com.au>
 */
class Aligent_WebsiteSwitcher_Block_Switcher extends Mage_Core_Block_Template
{
    protected function _construct()
    {
        parent::_construct();

        // Add lifetime
        $this->addData(array('cache_lifetime' => 3600));

        // Add cache tags
        $this->addCacheTag(array(
            Mage_Core_Model_Store::CACHE_TAG,
            Mage_Cms_Model_Block::CACHE_TAG,
        ));
    }

    public function getStores() {
        return Mage::app()->getStores();
    }
    
    public function getCurrentStoreId() {
        return Mage::app()->getStore()->getId();
    }

    public function getCacheKeyInfo()
    {
        return array(
            'ALIGENT_WEBSITE_SWITCHER_BLOCK',
            Mage::app()->getStore()->getId(),
            (int)Mage::app()->getStore()->isCurrentlySecure(),
            Mage::getDesign()->getPackageName(),
            Mage::getDesign()->getTheme('template')
        );
    }
}
