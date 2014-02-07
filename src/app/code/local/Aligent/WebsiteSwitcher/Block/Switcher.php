<?php
/**
 * app/code/local/Aligent/WebsiteSwitcher/Block/Switcher.php
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
 * Aligent_WebsiteSwitcher_Block_Switcher
 *
 * @category  Aligent
 * @package   Aligent_WebsiteSwitcher
 * @author    Luke Mills <luke@aligent.com.au>
 * @author    Jim O'Halloran <jim@aligent.com.au>
 * @copyright 2013-2014 Aligent Consulting.
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.aligent.com.au/
 */
class Aligent_WebsiteSwitcher_Block_Switcher extends Mage_Core_Block_Template
{

    /**
     * Gets an array of valid store objects indexed  by store id for the website switcher.
     *
     * @return array
     */
    public function getStores()
    {
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
     * Gets the current store object.
     *
     * @return Mage_Core_Model_Store
     */
    public function getCurrentStore()
    {
        return Mage::app()->getStore();
    }

    /**
     * Gets the current store Id.
     *
     * @return int
     */
    public function getCurrentStoreId()
    {
        return $this->getCurrentStore()->getId();
    }

    /**
     * Gets the current store name.
     *
     * @return null|string
     */
    public function getCurrentStoreName()
    {
        return $this->getCurrentStore()->getName();
    }
}
