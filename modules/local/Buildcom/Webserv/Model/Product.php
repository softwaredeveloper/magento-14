<?php
class Buildcom_Webserv_Model_Product extends Mage_Catalog_Model_Product {
	protected function _construct() {
		$this->_init('webserv/product');
		$this->_eventPrefix = 'webserv_product';
	}

	/**
	 * Retrieve Store Id
	 *
	 * @return int
	 */
	public function getStoreId()
	{
		if ($this->hasData('store_id')) {
			return $this->getData('store_id');
		}
		return Mage::app()->getStore()->getId();
	}

}