<?php
/**
 * @category    Aligent
 * @package     Aligent_WebsiteSwitcher
 * @copyright   Copyright (c) 2013 Aligent Consulting. (http://www.aligent.com.au)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * @author 		Luke Mills <luke@aligent.com.au>
 * @author      Swapna Palaniswamy <swapna@aligent.com.au>
 * @author      Jim O'Halloran <jim@aligent.com.au>
 */
class Aligent_WebsiteSwitcher_Model_Observer
{

    const HANDLE_PREFIX = 'aligent_websiteswitcher_';

    /**
     * Observe the controller_front_init_before event and set the store cookie
     * to the current store.
     */
    public function setStoreCookie() {
        Mage::app()->getCookie()->set(Mage_Core_Model_Store::COOKIE_NAME, Mage::app()->getStore()->getId(), true);
    }


    /**
     * Observes the controller_action_layout_load_before event amd sets
     * appropriate layout handles for the layout system to work with.
     */
    public function setLayoutHandles() {

        /** @var $oHelper Aligent_WebsiteSwitcher_Helper_Data */
        $oHelper = Mage::helper('aligent_websiteswitcher');

        /** @var $oUpdate Mage_Core_Model_Layout_Update */
        $oUpdate = Mage::app()->getLayout()->getUpdate();

        if (count(Mage::app()->getStores()) > 1) {

            if ($oHelper->canDisplayInMenu()) {
                $oUpdate->addHandle(self::HANDLE_PREFIX . 'display_in_menu');
            }

            if ($oHelper->canDisplayModal()) {
                if (!Mage::getSingleton('core/session')->getModalDisplayed()) {
                    Mage::getSingleton('core/session')->setModalDisplayed(true);
                    $oUpdate->addHandle(self::HANDLE_PREFIX . 'display_modal');
                }
            }
        }

    }

}
