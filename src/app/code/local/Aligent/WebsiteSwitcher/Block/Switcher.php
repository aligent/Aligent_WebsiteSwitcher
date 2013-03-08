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
    
    public function getStores() {
        return Mage::app()->getStores();
    }
    
    public function getCurrentStoreId() {
        return Mage::app()->getStore()->getId();
    }
    
}
