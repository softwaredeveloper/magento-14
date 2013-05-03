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

		if ( TRUE ) {
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
}