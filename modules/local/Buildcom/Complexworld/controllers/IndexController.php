<?php
class Buildcom_Complexworld_IndexController extends Mage_Core_Controller_Front_Action {
	public function indexAction() {
		if ( TRUE ) {
			$weblog2 = Mage::getModel('complexworld/eavblogpost');
			$weblog2->load(1);
			var_dump($weblog2);
		}
		$this->loadLayout();
		$this->renderLayout();
		return;
	}
	
	public function populateEntriesAction() {
		for ($i=0; $i< 10; $i++) {
			$weblog2 = Mage::getModel('complexworld/eavblogpost');
			$weblog2->setTitle('This is a test ' . $i);
			$weblog2->setContent('This is test content ' . $i);
			$weblog2->setDate(now());
			$weblog2->save();
		}
		echo 'Done' . PHP_EOL;
	}
	
	public function showCollectionAction() {
		$weblog2 = Mage::getModel('complexworld/eavblogpost');
		$entries = $weblog2->getCollection()
			->addAttributeToFilter(array(
					array('attribute' => 'title', '=' => 'This is a test 2'),
					array('attribute' => 'title', 'like' => 'This is a test 1%'),
			))
			->addAttributeToSelect('title')
			->addAttributeToSelect('date')
			->addAttributeToSelect('content');
		$entries->load();
		foreach ( $entries as $entry ) {
			echo '<h2>' . htmlentities($entry->getTitle()) . '</h2>' . PHP_EOL;
			echo '<p>Date: ' . htmlentities($entry->getDate()) . '</p>' . PHP_EOL;
			echo '<p>' . htmlentities($entry->getContent()) . '</p>' . PHP_EOL;
			//var_dump($entry->getData());
		}
		echo '<br />Done<br />' . PHP_EOL;
	}
	
	
}