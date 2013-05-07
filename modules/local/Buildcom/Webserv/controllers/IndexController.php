<?php
class Buildcom_Webserv_IndexController extends Mage_Core_Controller_Front_Action {
	public function indexAction() {
		echo '<pre>Web Services Test' . PHP_EOL;
		$model = Mage::getModel('webserv/product');
		$product = $model->load(163531);
		var_dump($product->getData());

		$model = Mage::getModel('catalog/product');
		$product = $model->load(167);
		var_dump($product->getData());
/*
		$model = Mage::getModel('webserv/product');
		$entries = $model->getCollection()
		->addAttributeToFilter(array(
				array('attribute' => 'product_id', '=' => 167),
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
*/		

		$helper = Mage::helper('webserv');
		$helper->setService('products/163531');
		//$helper->productId = '75050CB';
		//$helper->manufacturer = 'Delta';
		$product = $helper->execute();
		var_dump($product);
	
		echo '</pre>' . PHP_EOL;
	}

	public function coreAction() {
		echo '<pre>Core Test' . PHP_EOL;
		$model = Mage::getModel('catalog/product');
		$product=$model->load(167);
		var_dump($product->getData());
		echo '</pre>' . PHP_EOL;
	}
}