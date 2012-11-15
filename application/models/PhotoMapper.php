<?php

class Application_Model_PhotoMapper
{
	protected $_db;
	
	public function __construct() {
		// Récuperation de l'adapter
		$this->_db = Zend_Registry::get('db');
	}
	
	/**
	 * 
	 * Retourne une instance de Application_Model_Photo ou null
	 * @param int $idPhoto
	 * @return Application_Model_Photo || null
	 */
	public function getPhoto($idPhoto) {
		$query = '
		SELECT *
		FROM photos
		WHERE idPhoto = ?
		LIMIT 0,1
		';
		$bind = array($idPhoto);
		$statement = $this->_db->query($query, $bind);
		if($statement->rowCount() == 0)
			return null;
		
		return new Application_Model_Photo($statement->fetch());
		
	}
	
	public function getFirstPhoto($idProjet) {
		$query = '
		SELECT *
		FROM photos
		WHERE idProjet = ?
		ORDER BY idPhoto ASC
		LIMIT 0,1
		';
		$bind = array($idProjet);
		$row = $this->_db->fetchRow($query, $bind);
		
		if(is_array($row)) {
		
			// Instanciation d'un modèle de photo
			$photo = new Application_Model_Photo($row);
			return $photo;
		} else {
			return false;
		}
	}
	
	/**
	 * 
	 * Retourne une liste de Application_Model_Photo
	 * @param integer $idProjet
	 * @return array
	 */
	public function getPhotos($idProjet) {
		// Initialisation des variables
		$photos = array();
		
		$query = '
		SELECT *
		FROM photos
		WHERE idProjet = ?
		ORDER BY idPhoto ASC
		';
		$bind = array($idProjet);
		$rowset = $this->_db->fetchAll($query, $bind);
		
		foreach($rowset as $row) {
			$photos[] = new Application_Model_Photo($row);
		}
		return $photos;
	}
	
	/**
	 * 
	 * Retourne le nombre de photos liées à un projet
	 * @param integer $idProjet
	 * @return integer
	 */
	public function getCountPhotos($idProjet) {
		$query = '
		SELECT COUNT(idPhoto)
		FROM photos
		WHERE idProjet = ?
		';
		$bind = array($idProjet);
		return $this->_db->fetchOne($query, $bind);
	}
	
	public function getAllPhotos($offset, $limit) {
		// Initialisation des variables
		$photos = array();
		
		// Requête
		$query = '
		SELECT *
		FROM photos
		ORDER BY idPhoto ASC
		LIMIT '.$offset.', '.$limit;
		
		// Récuperation des photos
		$rowset = $this->_db->fetchAll($query);
		
		// Instanciation des models pour chaque photo
		foreach($rowset as $photo)
		{
			$photos[] = new Application_Model_Photo($photo);
		}
		
		return $photos;
	}
	
	/**
	 * 
	 * Insertion de la photo
	 * @param array $data
	 */
	public function insertPhoto($data) {
		return($this->_db->insert('photos', $data));
	}
	
	/**
	 * 
	 * Suppression de la photo
	 * @param integer $idPhoto
	 */
	public function deletePhoto($idPhoto) {
		$where = $this->_db->quoteInto('idPhoto = ?', $idPhoto);
		return($this->_db->delete('photos', $where));
	}
	
	public function deletePhotos($idProjet) {		
		// Récuperation de la liste des photos
		$photos = $this->getPhotos($idProjet);
		
		// Parcourt la liste des photos
		foreach($photos as $photo)
		{
			// Suppression de l'image
			unlink(APPLICATION_PATH.'/../public/docs/'.$photo->getSrc());

			// Suppression de l'enregistrement
			$this->deletePhoto($photo->getIdPhoto());
		}
		
		return count($photos);
	}
}
?>