<?php
/**
 * @category    Aligent
 * @package     Aligent_WebsiteSwitcher
 * @copyright   Copyright (c) 2013 Aligent Consulting. (http://www.aligent.com.au)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * @author 		Luke Mills <luke@aligent.com.au>
 * @author 		Jim O'Halloran <jim@aligent.com.au>
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


    /**
     * Returns true if the use_geoip parameter is turned on, AND the Aligent_GeoIP
     * extension is installed.  For backwards compatibility, we don't want to make
     * Aligent_GeoIP a dependency of this extension, but we do want to ensure GeoIP
     * features in this extension don't work without it.
     *
     * @return bool
     */
    public function canUseGeoIP() {
        if (Mage::getStoreConfigFlag('aligent_website_switcher/website_switcher/use_geoip')) {
            try {
                $oHelper = Mage::helper('aligent_geoip');
            } catch (Exception $e) {
                $oHelper = null;
            }
            if (!$oHelper) {
                Mage::log("Can't use Geolocation.  Aligent/GeoIP not installed or disabled.");
            } else {
                return true;
            }
        }
        return false;
    }


    /**
     * Use IP Geolocation to find the first store that can supply items to the
     * user's country.
     *
     * @return int
     */
    public function geoLocateToStoreId() {
        if ($this->canUseGeoIP()) {
            $vCountryCode = Mage::helper('aligent_geoip')->autodetectCountry();
            if ($vCountryCode !== false) {

                $iCurrentWebSiteId = Mage::app()->getStore()->getWebsiteId();
                $aStoreIds = Mage::getModel('core/store')->getCollection()
                    ->addFieldToFilter('website_id', array('eq' => $iCurrentWebSiteId))
                    ->getAllIds();

                $oConfigItem = Mage::getModel('core/config_data')->getCollection()
                    ->addFieldToFilter('scope', array('eq' => 'stores'))
                    ->addFieldToFilter('path', array('eq' => $this->getCountryParam()))
                    ->addFieldToFilter('value', array('finset' => $vCountryCode))
                    ->addFieldToFilter('scope_id', array('in' => $aStoreIds))
                    ->getFirstItem();

                if (!$oConfigItem->isObjectNew()) {
                    return $oConfigItem->getScopeId();
                }
            }
        }

        return Mage::app()->getStore()->getId();
    }


    /**
     * Return the name of the parameter we'll use to geoguess the most
     * appropriate store for the customer.
     *
     * @return string
     */
    public function getCountryParam() {
        return Mage::getStoreConfig('aligent_website_switcher/website_switcher/geoip_based_on');
    }

    public function getLimitToCurrentWebsite() {
        return Mage::getStoreConfigFlag('aligent_website_switcher/website_switcher/limit_to_website');
    }
}
