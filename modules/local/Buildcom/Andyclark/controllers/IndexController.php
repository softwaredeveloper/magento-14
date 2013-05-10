<?php
class Buildcom_Andyclark_IndexController extends Mage_Core_Controller_Front_Action {
	public function indexAction() {
		$this->loadLayout();
		$this->renderLayout();
		return;

		ob_start();
		echo '<pre>' . PHP_EOL;
		echo 'Build.com Andyclark test' . PHP_EOL;

		if ( FALSE ) {
			$model = Mage::getModel('catalog/product')->load(27);
			$price = $model->getPrice();
			print_r($price);
			$price += 10;
			$model->setPrice($price);
			$model->save();
		}

		if ( FALSE ) {
			$products_collection = Mage::getModel('catalog/product')
				->getCollection()
				->addAttributeToSelect('*')
				/*->addFieldToFilter('price', '5.00')*/;
			foreach ( $products_collection as $product) {
				print_r($product->getSku());
				echo PHP_EOL;
			}
		}

		if ( FALSE ) {
			$helper = Mage::helper('catalog');
			$translated_output = $helper->__('Magento is Great');
			print_r($translated_output);
			echo PHP_EOL;
		}

		echo '</pre>' . PHP_EOL;
		echo ob_get_clean();
		/*
		$this->loadLayout();
		$block = $this->getLayout()->createBlock('adminhtml/system_account_edit');
		$this->getLayout()->getBlock('content')->append($block);
		$this->renderLayout();
		*/
	}

	public function goodbyeAction() {
		$this->loadLayout();
		$this->renderLayout();
	}

	public function paramsAction() {
		echo '<dl>';
		foreach($this->getRequest()->getParams() as $key=>$value) {
			echo '<dt><strong>Param: </strong>'.$key.'</dt>';
			echo '<dt><strong>Value: </strong>'.$value.'</dt>';
		}
		echo '</dl>';
	}

	public function varienTestAction() {
		$thing_1 = new Varien_Object();
		$thing_1->setName('Richard')
			->setAge(24);
		$thing_2 = new Varien_Object();
		$thing_2->setName('Jane')
			->setAge(22);
		$thing_3 = new Varien_Object();
		$thing_3->setName('Spot')
			->setLastName('The Dog')
			->setAge(7);

		$collection_of_things = new Varien_Data_Collection();
		$collection_of_things
			->addItem($thing_1)
			->addItem($thing_2)
			->addItem($thing_3);

		foreach ( $collection_of_things as $thing ) {
			var_dump($thing->getData());
		}

		echo 'First Thing:' . $collection_of_things->getFirstItem()->getName() . '<br />' . PHP_EOL;
		echo 'Last Thing:' . $collection_of_things->getLastItem()->getName() . '<br />' . PHP_EOL;

		$age_7_items = $collection_of_things->getItemsByColumnValue('age', 7);
		echo 'First Age 7 Thing:' . $age_7_items[0]->getName() . '<br />' . PHP_EOL;

		echo 'Collection XML' . htmlentities($collection_of_things->toXml()) . '<br />' . PHP_EOL;
	}

	public function testAction() {
		$collection_of_products = Mage::getModel('catalog/product')
			->getCollection()
			//->addFieldToFilter('sku', array('eq' =>'n2610'))
			->addFieldToFilter('sku', array('in' => array('n2610', 'ABC123')))
			->addFieldToFilter('model', array('notnull' => TRUE))
			->addFieldToFilter('price', array('from' => 100, 'to' => 200))
			->addAttributeToSelect('*');
		//var_dump($collection_of_products->getFirstItem()->getData());
		echo (string)$collection_of_products->getSelect() . '<br />' . PHP_EOL;;
		echo 'Our collection now has ' . count($collection_of_products) . ' item(s)' . '<br />' . PHP_EOL;;
		var_dump($collection_of_products->getFirstItem()->getData());
	}

	public function test2Action() {
		$filter_a = array('like' => 'a%');
		$filter_b = array('like' => 'b%');
		$collection_of_products = Mage::getModel('catalog/product')
		->getCollection()
		->addFieldToFilter('sku', array($filter_a, $filter_b))
		->addAttributeToSelect('*');
		echo (string)$collection_of_products->getSelect() . '<br />' . PHP_EOL;;
		echo 'Our collection now has ' . count($collection_of_products) . ' item(s)' . '<br />' . PHP_EOL;;
		var_dump($collection_of_products->getFirstItem()->getData());
	}
}