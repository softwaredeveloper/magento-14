<?php
class Buildcom_Webserv_Model_Observer {
    public function addInventoryData($observer) {
        $product = $observer->getEvent()->getProduct();
        if ($product instanceof Mage_Catalog_Model_Product) {
            $productId = intval($product->getId());
            if (!isset($this->_stockItemsArray[$productId])) {
                $this->_stockItemsArray[$productId] = Mage::getModel('cataloginventory/stock_item');
            }
            $productStockItem = $this->_stockItemsArray[$productId];
            $productStockItem->assignProduct($product);
        }
		$product->setIsInStock(1);
		$product->setIsSalable(1);
        return $this;
	}
}