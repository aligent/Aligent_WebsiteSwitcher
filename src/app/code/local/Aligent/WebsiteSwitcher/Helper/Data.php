<?php
/**
 * @category    Aligent
 * @package     Aligent_WebsiteSwitcher
 * @copyright   Copyright (c) 2013 Aligent Consulting. (http://www.aligent.com.au)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * @author 		Luke Mills <luke@aligent.com.au>
 */
class Aligent_WebsiteSwitcher_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * @return bool
     */
    public function canDisplayInMenu() {
        return Mage::getStoreConfigFlag('aligent_website_switcher/website_switcher/display_in_menu');
    }
    
    public function canDisplayModal() {
        return Mage::getStoreConfigFlag('aligent_website_switcher/website_switcher/display_modal');
    }

}
