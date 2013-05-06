<?php
/**
 * @category    Aligent
 * @package     Aligent_WebsiteSwitcher
 * @copyright   Copyright (c) 2013 Aligent Consulting. (http://www.aligent.com.au)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * @author 		Luke Mills <luke@aligent.com.au>
 */
class Aligent_WebsiteSwitcher_Model_Observer
{

    public function setStoreCookie() {
        Mage::app()->getCookie()->set(Mage_Core_Model_Store::COOKIE_NAME, Mage::app()->getStore()->getId(), true);
    }

    /**
     * Sets appropriate layout handles for the layout system to work with
     */
    public function setLayoutHandles() {

        /** @var $helper Aligent_WebsiteSwitcher_Helper_Data */
        $helper = Mage::helper('aligent_websiteswitcher');

        /** @var $update Mage_Core_Model_Layout_Update */
        $update = Mage::app()->getLayout()->getUpdate();

        $handlePrefix = 'aligent_websiteswitcher_';

        if (count(Mage::app()->getStores()) > 1) {

            if ($helper->canDisplayInMenu()) {
                $update->addHandle($handlePrefix . 'display_in_menu');

            }

            if ($helper->canDisplayModal()) {
                $modalDisplayed='';
                $modalDisplayed= Mage::getSingleton('core/session')->getModalDisplayed();
                if($modalDisplayed){
                    return;
                }
                else{
                    $modalDisplayed=true;
                    Mage::getSingleton('core/session')->setModalDisplayed($modalDisplayed);
                    $update->addHandle($handlePrefix . 'display_modal');
                }
            }

        }

    }

}
