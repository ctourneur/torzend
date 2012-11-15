<?php

class Application_Model_CertificationMapper
{
	protected $_db;
	
	public function __construct() {
		// Récuperation de l'adapter
		$this->_db = Zend_Registry::get('db');
	}
	
	public function getCertifications($offset, $limit) {
		$query = 'SELECT * FROM certification ORDER BY id ASC LIMIT '.$offset.', '.$limit;
		return $this->_db->fetchAll($query, null, Zend_Db::FETCH_ASSOC);
	}
}

