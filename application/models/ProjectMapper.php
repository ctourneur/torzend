<?php
class Application_Model_ProjectMapper
{
	protected $_db;
	
	public function __construct() {
		// Récuperation de l'adapter
		$this->_db = Zend_Registry::get('db');
	}
	
	/**
	 * 
	 * Retourne un tableau de Application_Model_Project
	 * @param array $filters - Tableau des idTag désirés dans les projets à chercher
	 * @param integer $offset - Offset
	 * @param integer $limit - Nombre maximum de résultats à retourner
	 * @return array || null
	 */
	public function getProjects($filters=array(), $offset=0, $limit=10, $orderBy='date', $order='ASC' ) {
		// Initialisation des variables
		$projects = array();
		$bind = array();
		
		// Requête
		$query = '
		SELECT DISTINCT(p.idProjet), p.*, DATE_FORMAT(p.date, "%d/%m/%Y") as date 
		FROM projets p
		ORDER BY p.'.$orderBy.' '.$order;
		if($limit>0) {
			$query .= '
			LIMIT '.$offset.', '.$limit;
		}
		
		// Execution de la requête
		$rowset = $this->_db->fetchAll($query, $bind);
		
		if(count($rowset) == 0) return null;
		
		$photoMapper = new Application_Model_PhotoMapper();
		foreach($rowset as $project)
		{
			// Instanciation du modèle
			$modelProject = new Application_Model_Project($project);
			
			// Insertion du modele dans le tableau
			$projects[] = $modelProject;
		}
		return $projects;
	}
	
	public function getAllProjects() {
		return $this->getProjects(array(), 0, $this->count());
	}
	
	/**
	 * 
	 * Retourne une instance de Application_Model_Project ou null si le projet
	 * n'a pas été trouvé
	 * @param integer $idProjet
	 * @return Application_Model_Project || null
	 */
	public function getProject($idProjet) {
		// Requête
		$query = 'SELECT *, DATE_FORMAT(date, "%d/%m/%Y") as date FROM projets WHERE idProjet = ?';
		$bind = array($idProjet);
		$statement = $this->_db->query($query, $bind);
		
		if($statement->rowCount() == 0) return null;
		
		// Projet
		$row = $statement->fetch();
		$project = new Application_Model_Project($row);
		
		// Photos
		$photoMapper = new Application_Model_PhotoMapper();
		$photos = $photoMapper->getPhotos($project->getIdProjet());
		
		// Insertion des photos dans le projet
		$project->setPhotos($photos);
		
		return $project;
	}

	/**
	 * Retourne le nombre de projets correspondant aux critères saisis
	 * @param array $filters - Tableau des idTag désirés dans les projets à chercher
	 * @return integer
	 */
	public function count($filters=array()) {
		// Initialisation des variables
		$bind = array();
		
		// Requête
		$query = '
		SELECT DISTINCT(p.idProjet) FROM projets p';
		
		// Filtrage des tags
		if(count($filters)>0)
		{
			// From
			foreach($filters as $i => $idTag)
				$query .= ', projets_tags pt'.$i;
			// Jointures
			foreach($filters as $i => $idTag)
			{
				$where = ($i==0) ? 'WHERE' : 'AND';
				$query .= '
				'.$where.' p.idProjet = pt'.$i.'.idProjet ';
			}
			// Where
			foreach($filters as $i => $idTag)
			{
				$query .= '
				AND pt'.$i.'.idTag = ?';
				$bind[] = $idTag;
			}
		}
		$statement = $this->_db->query($query, $bind);
		
		return ($statement->rowCount());
	}
	
	/**
	 * 
	 * Retourne le dernier id inseré
	 * @return int
	 */
	public function getLastInsertId() {
		return $this->_db->lastInsertId('projets');
	}
	
	/**
	 * 
	 * Insertion d'un projet
	 * @param array $data
	 * return integer
	 */
	public function createProject($data) {
		// Création du projet
		$rowsInserted = $this->_db->insert('projets', $data);
		
		return $rowsInserted;
	}
	
	/**
	 * 
	 * Met à jour un projet
	 * @param integer $id
	 * @param array $data
	 * @return integer
	 */
	public function setProject($id, $data) {
		// Mise à jour du projet
		$where = $this->_db->quoteInto('idProjet = ?', $id);
		return $this->_db->update('projets', $data, $where);
	}
	
	public function deleteProject($id) {
		// Suppression des tags
		$tagMapper = new Application_Model_TagMapper();
		$tagMapper->deleteTagsToProject($id);
		
		// Suppression des photos
		$photoMapper = new Application_Model_PhotoMapper();
		$photoMapper->deletePhotos($id);
		
		// Suppression du projet
		$where = $this->_db->quoteInto('idProjet = ?', $id);
		return $this->_db->delete('projets', $where);
	}
}
?>