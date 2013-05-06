<?php
echo 'Running This Upgrade: ' . get_class($this) . '<br />' . PHP_EOL;
$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()->newTable($installer->getTable('weblog/blogpost'))
	->addColumn('blogpost_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned' => TRUE,
		'nullable' => FALSE,
		'primary' => TRUE,
		'identity' => TRUE,		
	), 'Blogpost ID')
	->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
		'nullable' => FALSE,
	), 'Blogpost Title')
	->addColumn('post', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
		'nullable' => TRUE,
	), 'Blogpost Body')
	->addColumn('date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
		'nullable' => TRUE,
	), 'Blogpost Date')
	->addColumn('timestamp', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
	), 'Blogpost Timestamp')
	->setComment('Buildcom weblog/blogpost entity table');
$installer->getConnection()->createTable($table);

$installer->endSetup();