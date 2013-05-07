<?php
class Buildcom_Webserv_Model_Resource_Product extends Buildcom_Webserv_Model_Resource_Webserv_Abstract
{
	protected function _construct()
	{
		$this->_init('products');
	}

	protected function _getRequestUri($unique_id)
	{
		return $this->_services_uri . '/' . $this->_service . '/' . rawurlencode($unique_id);
	}

	/**
	 * Map JSON object data to a Magento array
	 * 
	 * @param object $data
	 * @return array
	 */
	protected function _mapData($data)
	{
		$map = array(
				'entity_id' => 'uniqueId',
				// 'entity_type_id'
				// 'attribute_set_id'
				// 'type_id'
				'sku' => 'sku',
				'created_at' => 'dateAdded', // Times are in microseconds
				'updated_at' => 'modifiedDate', // Times are in microseconds
				//'has_options' => '',
				//'required_options' => '',
				'price' => 'cost', // TODO
				'cost' => '', // Assuming webservices 'cost' is actually 'price'
				'weight' => 'weight', // Do units match?
				'special_price' => '',
				'msrp' => '',
				'name' => '',
				'meta_title' => '',
				'description' => 'description',
				'short_description' => 'productTitle',
				'image' => '',
				'small_image' => '',
				'url_key' => '',
				'thumbnail' => '',
				'gift_message_available' => '',
				'custom_design' => '',
				//'options_container' => '',
				'manufacturer' => 'manufacturer',
				'status' => 'status', // Enum: 'stock' as 1 and 'discontinued' as 0
		);

		$result = array();		
		foreach ( $map as $mage_key => $webserv_key ) {
			$value = empty($data->$webserv_key) ? '' : $data->$webserv_key;
			switch ( $mage_key ) {
				case 'name':
					$value = ( empty($data->manufacturer) ? '' : $data->manufacturer )
						. ' '
						. ( empty($data->productId) ? '' : $data->productId );
					break;
				case 'created_at':
				case 'updated_at':
					$value = $this->formatDate($value/1000);
					break;
				case 'status':
					$value = $value == 'stock' ? 1 : 0;
					break;
			}
			$result[$mage_key] = $value;
		}
		
		return $result;
	}
}