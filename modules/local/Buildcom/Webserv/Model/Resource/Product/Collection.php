<?php
class Buildcom_Webserv_Model_Resource_Product_Collection extends Mage_Eav_Model_Entity_Collection_Abstract {
	protected function _construct() {
		$this->_init('webserv/product');
	}
}