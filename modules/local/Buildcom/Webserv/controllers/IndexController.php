<?php
class Buildcom_Webserv_IndexController extends Mage_Core_Controller_Front_Action {
	const OMC_UNIQUE_ID='1573671'; // Test product from OMC
	const MAGE_ENTITY_ID = 167; // Test product (sample data in Magento)

	public function indexAction() {
		$model = Mage::getModel('webserv/product');
		$product = $model->load(self::OMC_UNIQUE_ID);
		$this->_vardumpAsTable($product->getData());
	}

	/**
	 * Show a catalog/product
	 */
	public function catalogproductAction() {
		$model = Mage::getModel('catalog/product');
		$product = $model->load(self::MAGE_ENTITY_ID);
		$this->_vardumpAsTable($product->getData());
	}

	/**
	 * Show product details as listed in OMC
	 */
	public function omdirectAction() {
		$helper = Mage::helper('webserv');
		$helper->setService('products/' . self::OMC_UNIQUE_ID);
		//$helper->productId = '75050CB';
		//$helper->manufacturer = 'Delta';
		$product = $helper->execute();
		$this->_vardumpAsTable($product);
	}

	/**
	 * Product Collections
	 * TODO
	 */
	public function collectionAction() {
		$model = Mage::getModel('webserv/product');
		$entries = $model->getCollection()
		->addAttributeToFilter(array(
				array('attribute' => 'product_id', '=' => self::MAGE_ENTITY_ID),
				array('attribute' => 'title', 'like' => 'Dummy'),
		))
		->addAttributeToSelect('product_id')
		->addAttributeToSelect('title');
		$entries->load();
		foreach ( $entries as $entry ) {
			echo '<p>' . htmlentities($entry->getProductId()) . '</p>' . PHP_EOL;
			echo '<h2>' . htmlentities($entry->getTitle()) . '</h2>' . PHP_EOL;
			//var_dump($entry->getData());
		}
	}

	/**
	 * Output data in a formatted table
	 * @param array $data
	 * @return boolean
	 */
	protected function _vardumpAsTable($data) {
		if ( ! is_array($data) ) {
			return FALSE;
		}
//var_dump($data['stock_item']);
		echo '<table border="1">' . PHP_EOL;
		echo '	<tr>' . PHP_EOL;
		echo '		<th>Key</th>' . PHP_EOL;
		echo '		<th>Type</th>' . PHP_EOL;
		echo '		<th>Value</th>' . PHP_EOL;
		echo '	</tr>' . PHP_EOL;
		foreach ( $data as $key => $value ) {
			$type = gettype($value);
			echo '	<tr>' . PHP_EOL;
			echo '		<td>' . htmlentities($key) . '</td>' . PHP_EOL;
			echo '		<td>' . htmlentities($type) . '</td>' . PHP_EOL;
			echo '		<td>';
			if ( empty($type) || $type == 'array' || $type == 'object' ) {

				echo '<div style="width: 300px; overflow: auto;">' . PHP_EOL;
				var_dump($value);
				echo '</div>' . PHP_EOL;
			} else {
				echo htmlentities($value) . PHP_EOL;
			}
			echo '		</td>';
			echo '	</tr>' . PHP_EOL;
		}
		echo '</table>' . PHP_EOL;
		return TRUE;
	}
}