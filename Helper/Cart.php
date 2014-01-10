<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Perpetual
 * @package    Perpetual_MultiAdd
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Vivant_CmsProducts_Helper_Cart extends Mage_Core_Helper_Url
{
    /**
     * Return url to add multiple items to the cart
     * @return  url
     */
    public function getAddToCartUrl()
    {
        if ($currentCategory = Mage::registry('current_category')) {
            $continueShoppingUrl = $currentCategory->getUrl();
        } else {
            $continueShoppingUrl = $this->_getUrl('*/*/*', array('_current'=>true));
        }

        $params = array(
            Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED => Mage::helper('core')->urlEncode($continueShoppingUrl)
        );

        if ($this->_getRequest()->getModuleName() == 'checkout'
            && $this->_getRequest()->getControllerName() == 'cart') {
            $params['in_cart'] = 1;
        }
        return $this->_getUrl('checkout/cart/addmultiple', $params);
    }
}
?>