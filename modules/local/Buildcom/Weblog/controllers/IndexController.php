<?php
class Buildcom_Weblog_IndexController extends Mage_Core_Controller_Front_Action {
	public function getpostAction() {
		$params = $this->getRequest()->getParams();
		if ( empty($params['id']) || ( ! is_numeric($params['id']) ) ) {
			return FALSE;
		}

	    $blogpost = Mage::getModel('weblog/blogpost');
	    echo 'Loading the blogpost with an ID of ' . $params['id'];
	    $blogpost->load($params['id']);
	    $data = $blogpost->getData();
	    var_dump($data);
	}
	
	public function createpostAction() {
		$blogpost = Mage::getModel('weblog/blogpost');
		$blogpost->setTitle('Code Post!')
			->setPost('This post was created from code!')
			->save();
		echo 'post with ID ' . $blogpost->getId() . ' created';
	}
	
	public function editpostAction() {
		$params = $this->getRequest()->getParams();
		if ( empty($params['id']) || ( ! is_numeric($params['id']) ) ) {
			return FALSE;
		}

		$blogpost = Mage::getModel('weblog/blogpost');
		$blogpost->load($params['id'])
			->setTitle('The ' . $params['id'] . ' post!')
			->save();
		echo 'post edited';
	}
	
	public function deletepostAction() {
		$params = $this->getRequest()->getParams();
		if ( empty($params['id']) || ( ! is_numeric($params['id']) ) ) {
			return FALSE;
		}

		$blogpost = Mage::getModel('weblog/blogpost');
		$blogpost->load($params['id'])
			->delete();
		echo 'post ' . $params['id'] . ' removed';
	}
	
	public function showallpostsAction() {
echo 'TEST!!';
		$posts = Mage::getModel('weblog/blogpost')->getCollection();
		foreach ( $posts as $blogpost ) {
			echo '<h3>' . $blogpost->getTitle() . '</h3>' . PHP_EOL;
			echo nl2br($blogpost->getPost());
		}
	}
}