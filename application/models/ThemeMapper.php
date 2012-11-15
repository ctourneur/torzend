<?php

class Application_Model_ThemeMapper
{
	protected $_db;
	
	public function __construct() {
		// Récuperation de l'adapter
		$this->_db = Zend_Registry::get('db');	
	}
	
	/**
	 * 
	 * Récuperation de la liste des thèmes
	 * @param string $order
	 * @param string $dir
	 */
	public function getThemes($order='idTheme', $dir='ASC') {
		// Initialisation des variables
		$themes = array();
		
		// Récuperation des themes
		$query = 'SELECT * FROM themes ORDER BY '.$order.' '.$dir;
		$rowset = $this->_db->fetchAll($query);
		foreach($rowset as $row)
		{
			$themes[] = new Application_Model_Theme($row);
		}
		
		return $themes;
	}
	
	/**
	 * 
	 * Récuperation du thème
	 * @param int $idTheme
	 */
	public function getTheme($idTheme) {
		// Récuperation du thème
		$query = 'SELECT * FROM themes WHERE idTheme = ? LIMIT 0,1';
		$bind = array($idTheme);
		$row = $this->_db->fetchRow($query, $bind);
		if(!is_array($row)) return false;
		
		$theme = new Application_Model_Theme($row);
		
		return $theme;
	}
}