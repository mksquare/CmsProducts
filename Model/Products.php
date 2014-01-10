<?php
class Vivant_CmsProducts_Model_Products extends Mage_Catalog_Model_Product
{
    public function getItemsCollection($productIds)
    {
		$productsarray = explode(',',$productIds);
		$products = array();
	  foreach($productsarray as $productid)
	  {

		$products[] = Mage::getModel('catalog/product')->load($productid);  
	  }
	          //Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
        return $products;
    }
}