<?php
$installer = $this;
$installer->startSetup();
$installer->addEntityType('complexworld_eavblogpost', array(
	'entity_model' => 'complexworld/eavblogpost', // URI passed into Mage::getModel()
	'table' => 'complexworld/eavblogpost', // Resource URI <complexworld_resource>...<eavblogpost><table>eavblog_posts</table>...
));
$installer->createEntityTables($this->getTable('complexworld/eavblogpost'));
$this->addAttribute('complexworld_eavblogpost', 'title', array(
	'type'		=>	'varchar', // EAV attribute type, not MySQL column type
	'label'		=>	'Title',
	'input'		=>	'text',
	'class'		=>	'',
	'backend'	=>	'',
	'frontend'	=>	'',
	'source'	=>	'',
	'required'	=>	TRUE,
	'user_defined'	=>	TRUE,
	'default'		=>	'',
	'unique'	=>	FALSE,
));
$this->addAttribute('complexworld_eavblogpost', 'content', array(
	'type'		=>	'text',
	'label'		=>	'Content',
	'input'		=>	'textarea',
));
$this->addAttribute('complexworld_eavblogpost', 'date', array(
	'type'		=>	'datetime',
	'label'		=>	'Post Date',
	'input'		=>	'datetime',
	'required'	=>	FALSE,
));


$installer->endSetup();