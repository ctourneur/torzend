<?php

class Application_Model_Theme
{
	protected $_idTheme;
	protected $_nom;
	protected $_layout;
	
	public function __construct($options=null) {
		if(is_array($options))
		{
			foreach($options as $key => $value)
			{
				$method = 'set'.ucfirst($key);
				if(method_exists($this, $method)) $this->$method($value);
			}
		}
	}

	public function getIdTheme() {
		return $this->_idTheme;
	}
	
	public function getNom() {
		return $this->_nom;
	}
	
	public function getLayout() {
		return $this->_layout;
	}
	
	public function setIdTheme($idTheme) {
		$this->_idTheme = $idTheme;
		return $this;
	}
	
	public function setNom($nom) {
		$this->_nom = $nom;
		return $this;
	}
	
	public function setLayout($layout) {
		$this->_layout = $layout;
		return $this;
	}
}