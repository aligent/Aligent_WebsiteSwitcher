<?php
/**
 * app/code/local/Aligent/WebsiteSwitcher/Helper/Data.php
 *
 * @category  Aligent
 * @package   Aligent_WebsiteSwitcher
 * @author    Luke Mills <luke@aligent.com.au>
 * @author    Jim O'Halloran <jim@aligent.com.au>
 * @copyright 2013-2014 Aligent Consulting.
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.aligent.com.au/
 */

/**
 * Aligent_WebsiteSwitcher_Helper_Data
 *
 * @category  Aligent
 * @package   Aligent_WebsiteSwitcher
 * @author    Luke Mills <luke@aligent.com.au>
 * @author    Jim O'Halloran <jim@aligent.com.au>
 * @copyright 2013-2014 Aligent Consulting.
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.aligent.com.au/
 */
class Aligent_WebsiteSwitcher_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Returns whether or not the website switcher can be displayed in the menu, based on store config.
     *
     * @return bool
     */
    public function canDisplayInMenu()
    {
        return Mage::getStoreConfigFlag('aligent_website_switcher/website_switcher/display_in_menu');
    }

    /**
     * Returns whether or not the website switcher can be displayed in a modal, based on store config.
     *
     * @return bool
     */
    public function canDisplayModal()
    {
        return Mage::getStoreConfigFlag('aligent_website_switcher/website_switcher/display_modal');
    }

    /**
     * Whether or not the Aligent_GeoIP module is available, and set to be used in the store config.
     *
     * Returns true if the use_geoip parameter is turned on, AND the Aligent_GeoIP
     * extension is installed.  For backwards compatibility, we don't want to make
     * Aligent_GeoIP a dependency of this extension, but we do want to ensure GeoIP
     * features in this extension don't work without it.
     *
     * @return bool
     */
    public function canUseGeoIP()
    {
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
     * Use IP Geolocation to find the first store that can supply items to the user's country.
     *
     * @return int
     */
    public function geoLocateToStoreId()
    {
        if ($this->canUseGeoIP()) {
            $vCountryCode = Mage::helper('aligent_geoip')->autodetectCountry();
            if ($vCountryCode !== false) {
                $vCountryCode = strtoupper($vCountryCode);

                $iCurrentWebSiteId  = Mage::app()->getStore()->getWebsiteId();
                $storeIdsCollection = Mage::getModel('core/store')->getCollection();

                if ($this->getLimitToCurrentWebsite()) {
                    $storeIdsCollection->addFieldToFilter('website_id', array('eq' => $iCurrentWebSiteId));
                } else {
                    $storeIdsCollection->addFieldToFilter('code', array('neq' => 'admin'));
                }

                $aStoreIds = $storeIdsCollection->getAllIds();

                foreach ($aStoreIds as $storeId) {
                    $countries = explode(',', Mage::getStoreConfig($this->getCountryParam(), $storeId));
                    if (in_array($vCountryCode, $countries)) {
                        return $storeId;
                    }
                }
            }
        }

        return Mage::app()->getStore()->getId();
    }

    /**
     * Parameter to use to geo-guess the most appropriate store for the customer.
     *
     * @return string
     */
    public function getCountryParam()
    {
        return Mage::getStoreConfig('aligent_website_switcher/website_switcher/geoip_based_on');
    }

    /**
     * Whether or not to limit website switcher selection to stores within the current website.
     *
     * @return bool
     */
    public function getLimitToCurrentWebsite()
    {
        return Mage::getStoreConfigFlag('aligent_website_switcher/website_switcher/limit_to_website');
    }
}
