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

    /**
     * @return array
     */
    public function getStores() {
        $aStores = Mage::app()->getStores();
        if (Mage::helper('aligent_websiteswitcher')->getLimitToCurrentWebsite()) {
            $iWebsiteId = $this->getCurrentStore()->getWebsiteId();
            foreach ($aStores as $iIdx => $oStore) {
                if ($oStore->getWebsiteId() != $iWebsiteId) {
                    unset($aStores[$iIdx]);
                }
            }
        }
        return $aStores;
    }

    /**
     * @return Mage_Core_Model_Store
     */
    public function getCurrentStore() {
        return Mage::app()->getStore();
    }

    /**
     * @return int
     */
    public function getCurrentStoreId() {
        return $this->getCurrentStore()->getId();
    }

    /**
     * @return null|string
     */
    public function getCurrentStoreName() {
        return $this->getCurrentStore()->getName();
    }
    
}
