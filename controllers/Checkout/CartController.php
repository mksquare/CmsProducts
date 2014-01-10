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

require_once 'Mage/Checkout/controllers/CartController.php';

class Vivant_CmsProducts_Checkout_CartController extends Mage_Checkout_CartController
{
     /**
     * Adding multiple products to shopping cart action
     * based on Mage_Checkout_CartController::addAction()
     * see also http://www.magentocommerce.com/boards/viewthread/8610/
     * and http://www.magentocommerce.com/wiki/how_to_overload_a_controller
     */
    public function addmultipleAction()
    {
        $productIds = $this->getRequest()->getParam('productsids');
		$allproducts = array();
		foreach( $productIds as $productId) {
		 	try{
			$product = Mage::getModel('catalog/product')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->load($productId);
			$allproducts[] = $productId;		
			}catch (Mage_Core_Exception $e) {
				 Mage::getSingleton('checkout/session')->addNotice($productId . ': ' . $e->getMessage());
			}
			
		}
		
		$cart = $this->_getCart();
		$cart->addProductsByIds($allproducts);
		$cart->save();

		if (!is_array($productIds)) {
            $this->_goBack();
            return;
        }
   // echo "<pre>";print_r($productIds);exit;
        foreach( $productIds as $productId) {
            try {
                $qty = $this->getRequest()->getParam('qty' . $productId, 0);
                if ($qty <= 0) continue; // nothing to add
                
                $cart = $this->_getCart();
                
				$product = Mage::getModel('catalog/product')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->load($productId)
                    ->setConfiguredAttributes($this->getRequest()->getParam('super_attribute'))
                    ->setGroupedProducts($this->getRequest()->getParam('super_group', array()));
					//echo "<pre>";print_r($product->getData());
					
                $eventArgs = array(
                    'product' => $product,
                    'qty' => $qty,
                    'additional_ids' => array(),
                    'request' => $this->getRequest(),
                    'response' => $this->getResponse(),
                );
    
                Mage::dispatchEvent('checkout_cart_before_add', $eventArgs);
    
                $cart->addProduct($product, $qty);
    
                Mage::dispatchEvent('checkout_cart_after_add', $eventArgs);
    
                //$cart->save();
    
                Mage::dispatchEvent('checkout_cart_add_product', array('product'=>$product));
    
                if (!$this->_getSession()->getNoCartRedirect(true)) {
                if (!$cart->getQuote()->getHasError()){
				$message = $this->__('%s was successfully added to your shopping cart.', $product->getName());    
				 Mage::getSingleton('checkout/session')->addSuccess($message);
				}}
            }
            catch (Mage_Core_Exception $e) {
                if (Mage::getSingleton('checkout/session')->getUseNotice(true)) {
                    Mage::getSingleton('checkout/session')->addNotice($productId->getName() . ': ' . $e->getMessage());
                }
                else {
                    Mage::getSingleton('checkout/session')->addError($product->getName() . ': ' . $e->getMessage());
                }
            }
            catch (Exception $e) {
                Mage::getSingleton('checkout/session')->addException($e, $this->__('Can not add item to shopping cart'));
            }
        }
        $this->_goBack();
    }
}
?>