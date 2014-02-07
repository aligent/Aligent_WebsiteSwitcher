<?php
/**
 * app/code/local/Aligent/WebsiteSwitcher/Model/Source/Addressparam.php
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
 * Source model used in the selection of Billing/Shipping address for IP to store Geolocation.
 *
 * @category  Aligent
 * @package   Aligent_WebsiteSwitcher
 * @author    Luke Mills <luke@aligent.com.au>
 * @author    Jim O'Halloran <jim@aligent.com.au>
 * @copyright 2013-2014 Aligent Consulting.
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.aligent.com.au/
 */
class Aligent_WebsiteSwitcher_Model_Source_Addressparam
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 'general/country/allow',
                'label' => Mage::helper('aligent_websiteswitcher')->__('Billing Address')
            ),
            array(
                'value' => 'general/country/allow_shipping',
                'label' => Mage::helper('aligent_websiteswitcher')->__('Shipping Address')
            ),
            array(
                'value' => 'aligent_website_switcher/website_switcher/geolocate_to_store',
                'label' => Mage::helper('aligent_websiteswitcher')->__('Custom')
            ),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {

        return array(
            'general/country/allow'          => Mage::helper('adminhtml')->__('Billing Address'),
            'general/country/allow_shipping' => Mage::helper('adminhtml')->__('Shipping Address'),
            'aligent_website_switcher/website_switcher/geolocate_to_store'
                                             => Mage::helper('adminhtml')->__('Custom'),
        );
    }
}
