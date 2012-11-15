<?php

class Application_Model_Project
{
	protected $_idProjet;
	protected $_nom;
	protected $_type;
	protected $_url;
	protected $_texte;
	protected $_tags;
	protected $_photos;
	protected $_date;
	
	// Constructeur
	public function __construct($options=null) {
		
		// Initialisation des valeurs du modèle
		if(is_array($options))
		{
			foreach($options as $key => $value)
			{
				$method = 'set'.ucfirst($key);
				if(method_exists($this, $method)) $this->$method($value);
			}
		}
	}
	
	// Getters
	public function getIdProjet() {
		return $this->_idProjet;
	}
	
	public function getNom() {
		return $this->_nom;	
	}

	public function getType() {
		return $this->_type;
	}
	
	public function getUrl() {
		return $this->_url;
	}
	
	public function getTexte() {
		return $this->_texte;
	}
	
	public function getTags() {
		return $this->_tags;
	}
	
	public function getDate() {
		return $this->_date;
	}
	
	public function getPhotos() {
		return $this->_photos;
	}
	
	// Setters
	public function setIdProjet($idProjet) {
		$this->_idProjet = $idProjet;
		return $this;
	}
	
	public function setNom($nom) {
		$this->_nom = $nom;
		return $this;
	}

	public function setType($type) {
		$this->_type = $type;
		return $this;
	}

	public function setUrl($url) {
		$this->_url = $url;
		return $this;
	}
	
	public function setTexte($texte) {
		$this->_texte = $texte;
		return $this;
	}
	
	public function setTags($tags) {
		$this->_tags = $tags;
		return $this;
	}
	
	public function setDate($date) {
		$this->_date = $date;
		return $this;
	}
	
	public function setPhotos($photos) {
		$this->_photos = $photos;
		return $this;
	}
	
	public function getTagsValues() {
		$values = array();
		if(is_array($this->_tags))
		{
			foreach($this->_tags as $tag)
			{
				$values[] = $tag->getIdTag();
			}
		}
		return $values;
	}
	
	public function getTagsLabels() {
		$values = array();
		if(is_array($this->_tags))
		{
			foreach($this->_tags as $tag)
			{
				$values[] = $tag->getTag();
			}
		}
		return $values;
	}
	
	public function getTagsLabelsToString() {
		return implode(', ',$this->getTagsLabels());
	}
	
	public function addPhoto($photo) {
		if(!is_array($this->_photos))
			$this->_photos = array();
			
		$this->_photos[] = $photo;
		
	}
	
	/**
	 * 
	 * Retourne un extrait du texte, l'extrait sera récuperé à l'interieur d'une balise p
	 * @param integer $words
	 * @return string
	 */
	public function getExtract($words=30) {
		// Récuperation du premier paragraphe
		preg_match('#<p>([^<]+)</p>#', $this->_texte, $matches);
		
		// Extrait
		$extrait = (isset($matches[1])) ? $matches[1] : '';
		
		// Récuperation des 40 premiers mots
		$extraitArray = explode(' ', $extrait);
		
		if(count($extraitArray)>$words)
		{
			$extraitArrayTemp = array();
			for($i=0;$i<$words;$i++)
			{
				$extraitArrayTemp[] = $extraitArray[$i];
			}
			$extrait = implode(' ', $extraitArrayTemp).'...';
		}
		
		return $extrait;
	}
	
	/**
	 * 
	 * Determine si le projet a au moins une photo définie
	 * @return bool
	 */
	public function hasPhotos() {
		return (is_array($this->_photos) && count($this->_photos)>0) ? true : false;
	}
	
	/**
	 * 
	 * Retourne une instance de Application_Model_Photo ou null
	 * @return mixed
	 */
	public function getPhoto() {
		return ($this->hasPhotos()) ? $this->_photos[0] : null;
	}
	
	public function getCountPhotos() {
		return count($this->getPhotos());
	}
}
?>