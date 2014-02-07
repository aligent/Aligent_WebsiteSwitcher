<?php
/**
 * app/code/local/Aligent/WebsiteSwitcher/Model/Observer.php
 *
 * @category  Aligent
 * @package   Aligent_WebsiteSwitcher
 * @author    Luke Mills <luke@aligent.com.au>
 * @author    Swapna Palaniswamy <swapna@aligent.com.au>
 * @author    Jim O'Halloran <jim@aligent.com.au>
 * @copyright 2013-2014 Aligent Consulting.
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.aligent.com.au/
 */

/**
 * Aligent_WebsiteSwitcher_Model_Observer
 *
 * @category  Aligent
 * @package   Aligent_WebsiteSwitcher
 * @author    Luke Mills <luke@aligent.com.au>
 * @author    Swapna Palaniswamy <swapna@aligent.com.au>
 * @author    Jim O'Halloran <jim@aligent.com.au>
 * @copyright 2013-2014 Aligent Consulting.
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.aligent.com.au/
 */
class Aligent_WebsiteSwitcher_Model_Observer
{

    const HANDLE_PREFIX = 'aligent_websiteswitcher_';

    /**
     * Observe the controller_front_init_before event and checks whether the ___store get param is set.
     *
     * This is necessary because Mage_Core_Model_App::_checkGetStore() (/app/code/core/Mage/Core/Model/App.php:552)
     * deletes the store cookie if the get param is used and is set to the default store for a website (not necessarily
     * the default website). This has the effect of defaulting to the default store of the default website on the next
     * page load, which is not intended.
     *
     * @return Aligent_WebsiteSwitcher_Model_Observer
     */
    public function checkGetStore()
    {

        // Adapted from Mage_Core_Model_App::_checkGetStore().

        $stores        = Mage::app()->getStores();
        $indexedStores = array();
        foreach ($stores as $_store) {
            $indexedStores[$_store->getCode()] = $_store;
        }

        if (empty($_GET)) {
            return $this;
        }

        // @TODO Check XML_PATH_STORE_IN_URL.
        if (!isset($_GET['___store'])) {
            return $this;
        }

        $store = $_GET['___store'];
        if (!isset($indexedStores[$store])) {
            return $this;
        }

        $storeObj = $indexedStores[$store];
        if (!$storeObj->getId() || !$storeObj->getIsActive()) {
            return $this;
        }

        if (Mage::app()->getStore()->getCode() == $store) {
            Mage::app()->getCookie()->set(Mage_Core_Model_Store::COOKIE_NAME, $store, true);
        }

        return $this;
    }

    /**
     * Observe the controller_front_init_before event and set the store cookie to the current store.
     *
     * @return void
     */
    public function setStoreCookie()
    {
        $iCurrentStoreId = Mage::app()->getCookie()->get(Mage_Core_Model_Store::COOKIE_NAME);
        if ($iCurrentStoreId === false) {
            if (Mage::helper('aligent_websiteswitcher')->canUseGeoIP()) {
                $iGeoStore = Mage::helper('aligent_websiteswitcher')->geoLocateToStoreId();

                if ($iGeoStore !== Mage::app()->getStore()->getId()) {
                    $oStore = Mage::getModel('core/store')->load($iGeoStore);

                    Mage::app()->getCookie()->set(Mage_Core_Model_Store::COOKIE_NAME, $oStore->getCode(), true);

                    Mage::app()->init($oStore->getCode(), 'store');
                }
            }
        } else {
            Mage::log("Store Id: " . Mage::app()->getStore()->getId() . " Cookie Store: " . $iCurrentStoreId);
            Mage::app()->getCookie()->set(Mage_Core_Model_Store::COOKIE_NAME, Mage::app()->getStore()->getCode(), true);
        }
    }

    /**
     * Observes the controller_action_layout_load_before event amd sets appropriate layout handles for the layout system to work with.
     *
     * @return void
     */
    public function setLayoutHandles()
    {

        /** @var $oHelper Aligent_WebsiteSwitcher_Helper_Data */
        $oHelper = Mage::helper('aligent_websiteswitcher');

        /** @var $oUpdate Mage_Core_Model_Layout_Update */
        $oUpdate = Mage::app()->getLayout()->getUpdate();

        if (count(Mage::app()->getStores()) > 1) {

            if ($oHelper->canDisplayInMenu()) {
                $oUpdate->addHandle(self::HANDLE_PREFIX . 'display_in_menu');
            }

            if ($oHelper->canDisplayModal()) {
                $oUpdate->addHandle(self::HANDLE_PREFIX . 'display_modal');
            }
        }
    }
}
