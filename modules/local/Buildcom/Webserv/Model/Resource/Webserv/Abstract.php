<?php
/**
 * 
 * @see Mage_Catalog_Model_Resource_Abstract
 * @see Mage_Core_Model_Resource_Abstract
 *
 */
abstract class Buildcom_Webserv_Model_Resource_Webserv_Abstract
{
	// TODO Use admin to set URI
	const PRODUCTION_URI = 'http://webservices.sys.id.build.com:8080/build-webservices-1.0.0/';
	const DEV_URI = 'http://devbox2.build.internal:8080/build-webservices-1.0.0/services';
	protected $_services_uri;

	protected $_serializableFields   = array();
	protected $_service;

	/**
	 * Main constructor
	 */
	public function __construct()
	{
		/**
		 * Please override this one instead of overriding real __construct constructor
		 */
		$this->_construct();
	}

	abstract protected function _construct();
	abstract protected function _getRequestUri($unique_id);
	abstract protected function _mapData($data);

	protected function _init($service)
	{
		$this->_service = $service;
		$this->_services_uri = self::DEV_URI;
	}
public function getIdFieldName() {
	return 'entity_id';
}

	/**
	 * Perform actions after object load
	 *
	 * @param Varien_Object $object
	 * @return Mage_Core_Model_Resource_Db_Abstract
	 */
	protected function _afterLoad(Mage_Core_Model_Abstract $object)
	{
		return $this;
	}

	public function load(Mage_Core_Model_Abstract $object, $value, $field = null)
	{
		$request_uri = $this->_getRequestUri($value);
		$result = $this->_get_request($request_uri);
		$data = $this->_mapData($result);

		if ($data) {
			$object->setData($data);
		}

		$this->unserializeFields($object);
		$this->_afterLoad($object);
	
		return $this;
	}

	/**
	 * Execute get request
	 * @param string $request_uri
	 */
	protected function _get_request($request_uri) {
		try {
			@$json_results = file_get_contents($request_uri);
			return json_decode($json_results);
		} catch (Exception $e) {
			return FALSE;
		}
	}

	public function unserializeFields(Mage_Core_Model_Abstract $object)
	{
		foreach ($this->_serializableFields as $field => $parameters) {
			list($serializeDefault, $unserializeDefault) = $parameters;
			$this->_unserializeField($object, $field, $unserializeDefault);
		}
	}

	/**
	 * Format date to internal format
	 *
	 * @param string|Zend_Date $date
	 * @param bool $includeTime
	 * @return string
	 */
	public function formatDate($date, $includeTime=true)
	{
		return Varien_Date::formatDate($date, $includeTime);
	}
	
	/**
	 * Convert internal date to UNIX timestamp
	 *
	 * @param string $str
	 * @return int
	 */
	public function mktime($str)
	{
		return Varien_Date::toTimestamp($str);
	}
}