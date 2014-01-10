<?php
class Vivant_CmsProducts_Block_List extends Mage_Catalog_Block_Product_Abstract
{
    protected $_itemCollection = null;
 
    public function getItems()
    {
     	$products = $this->getProductIds();
		
        if (!$products)
            return false;
			
        if (is_null($this->_itemCollection)) {
            $this->_itemCollection = Mage::getModel('vivant_cmsproducts/products')->getItemsCollection($products);
        }
	   //echo "<pre>";print_r($this->_itemCollection->getData());exit;
        return $this->_itemCollection;
    }
}