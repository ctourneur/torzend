<?php

class Application_Model_Photo
{
	protected $_idPhoto;
	protected $_idProjet;
	protected $_src;
	protected $_titre;
	protected $_description;
	protected $_dir;
	
	public function __construct($options=null) {
		// Initialisation du répertoire
		$this->_dir = APPLICATION_PATH.'/../www/docs';
		
		// Options
		if(is_array($options))
		{
			foreach($options as $key => $value)
			{
				$method = 'set'.ucfirst($key);
				if(method_exists($this, $method)) $this->$method($value);
			}
		}
	}
	
	public function getIdPhoto() {
		return $this->_idPhoto;
	}
	
	public function getIdProjet() {
		return $this->_idProjet;
	}
	
	public function getSrc() {
		return $this->_src;
	}
	
	public function getTitre() {
		return $this->_titre;
	}
	
	public function getDescription() {
		return $this->_description;
	}
	
	public function setIdPhoto($idPhoto) {
		$this->_idPhoto = $idPhoto;
		return $this;
	}
	
	public function setIdProjet($idProjet) {
		$this->_idProjet = $idProjet;
		return $this;
	}
	
	public function setSrc($src) {
		$this->_src = $src;
		return $this;
	}
	
	public function setTitre($titre) {
		$this->_titre = $titre;
		return $this;
	}
	
	public function setDescription($description) {
		$this->_description = $description;
		return $this;
	}
	
	public function getPath() {
		return '/docs/'.$this->getSrc();
	}
	
	public function getThumb($dimension) { 
		if(file_exists($this->_dir.'/'.$this->getSrc()))
		{
			// Récuperation du filename
			$filename = pathinfo($this->getPath(), PATHINFO_FILENAME);
			// Récuperation du dirname
			$dirname = pathinfo($this->getPath(), PATHINFO_DIRNAME);
			// Récuperation de l'extension
			$extension = pathinfo($this->getPath(), PATHINFO_EXTENSION);
			
			// Basename
			$basename = $filename.'_'.$dimension.'.'.$extension;
			
			// Vérification que la thumb existe
			if(file_exists($this->_dir.'/'.$basename))
			{	
				// Récuperation de la thumb demandée
				$thumb = $dirname.'/'.$basename;
			}
			else
			{
				// Inclusion de la library
				require_once(realpath(APPLICATION_PATH.'/../library/PhpThumb/ThumbLib.inc.php'));
				
				// Création de la miniature
				$phpThumb = PhpThumbFactory::create($this->_dir.'/'.$this->getSrc());
				$dim = explode('x', $dimension);
				
				// Redimensionnement
				$phpThumb->adaptiveResize($dim[0], $dim[1]);
		
				// Sauvegarde de l'image
				$phpThumb->save($this->_dir.'/'.$basename);
				
				// Récuperation de la thumb demandée
				$thumb = $dirname.'/'.$basename;
			}
			
			return $thumb;
		}
		
		return false;
	}
}
?>