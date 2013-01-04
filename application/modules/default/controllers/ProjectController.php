<?php

class Default_ProjectController extends Zend_Controller_Action
{

    protected $_defaultPage = null;
    protected $_defaultLimit = null;
    protected $_defaultOrder = null;
    protected $_defaultDir = null;
    protected $_defaultDisplay = null;
    protected $_defaultType = null;

    public function init()
    {
    	// Récuperation de la page
    	$page = $this->view->navigation()->findByUri($this->getRequest()->getPathInfo());
    	if($page)
    	{
    		// Activation de la page pour le breadcrumbs
    		$page->setActive(true);
    		
    		// Ajout du titre à la page
    		if(!Zend_Registry::isRegistered('title')) {
    			Zend_Registry::set('title', true);
    			$this->view->headTitle($page->title, 'PREPEND');
    		}
    	}
    	
        // Récuperation des valeurs par défaut dans la base de données
        // A FAIRE
        $this->_defaultPage = 1;
        $this->_defaultLimit = 9;
        $this->_defaultOrder = 'date';
        $this->_defaultDir = 'DESC';
        $this->_defaultDisplay = 'list';
        $this->_defaultType = 'indifferent';
    }

    public function indexAction()
    {
    	// Redirige vers la liste
		$this->_forward('list');
    }

    public function listAction()
    {
    	// Validator
    	$digitsValidator = new Zend_Validate_Digits();
    	$inArrayValidator = new Zend_Validate_InArray(array('mosaic', 'list'));
    	$typesValidator = new Zend_Validate_InArray(array('vitrine', 'ecommerce', 'application'));
    	
    	// Mapper
    	$projectMapper = new Application_Model_ProjectMapper();
    	$photoMapper = new Application_Model_PhotoMapper();
    	
    	// Filtres
    	$filters = array();
    	
    	// Paramètres
    	$params = array(); 
    	
    	// Récuperation du display
		if($inArrayValidator->isValid($this->_getParam('display')))
		{
			$display = $this->_getParam('display');
			$params['display'] = $display;
		}
		else
		{
			$display = $this->_defaultDisplay;
		}
    	
    	// Récuperation de la page
    	if($digitsValidator->isValid($this->_getParam('page')))
    	{
    		$page = $this->_getParam('page');
    		$params['page'] = $page;
    	}
    	else
    	{
    		$page = $this->_defaultPage;
    	}
    	
    	// Récuperation de la limit
    	if($digitsValidator->isValid($this->_getParam('limit')) || $this->_getParam('limit') == 'all')
    	{
    		$limit = $this->_getParam('limit');
    		$params['limit'] = $limit;
    	}
    	else
    	{
    		$limit = $this->_defaultLimit;
    	}
    	
    	// Récuperation du type
    	if($typesValidator->isValid($this->_getParam('type')))
    	{
    		$filters['type'] = $this->_getParam('type');
    		$params['type'] = $this->_getParam('type');
    		$type = $this->_getParam('type');
    	}
    	else
    	{
    		$type = $this->_defaultType;
    	}
    	
    	// Récuperation de l'orderBy
    	$order = $this->_defaultOrder;
    	
    	// Récuperation de l'order
    	$dir = $this->_defaultDir;
    	
    	// URL Display
    	$displayParams = $params;
    	$displayParams['display'] = '%display%';
    	$this->view->displayUrl = urldecode('?'.http_build_query($displayParams));
    	
    	// URL Limit
    	$limitParams = $params;
    	$limitParams['limit'] = '%limit%';
    	$this->view->limitUrl = urldecode('?'.http_build_query($limitParams));
    	
    	// URL Page
    	$pageParams = $params;
    	$pageParams['page'] = '%page%';
    	$this->view->pageUrl = urldecode('?'.http_build_query($pageParams));
    	
    	// URL Type
    	$typeParams = $params;
    	$typeParams['type'] = '%type%';
    	$this->view->typeUrl = urldecode('?'.http_build_query($typeParams));
    	
		// Nombre de projets total
    	$count = $projectMapper->count($filters);
    	$this->view->count = $count;
    	
    	// Récuperation des projets
    	$projectsPerPage = ($limit == 'all') ? $this->view->count : $limit;
    	
    	// Calcul du nombre de pages
    	$pagesCount = ceil($count/$projectsPerPage);
    	$this->view->pagesCount = $pagesCount;
    	
    	// Chargement de la view
    	if($display == 'mosaic') $this->_helper->viewRenderer('mosaic');
    	
    	// Calcul du offset
    	if($page > $pagesCount) $page = $pagesCount;
    	$offset = (int)(($page-1)*$limit);
    	
    	// Récuperation des projets
    	$projects = $projectMapper->getProjects($filters, $offset, $limit, $order, $dir);
    	for($i=0;$i<count($projects);$i++)
    	{
    		$project = $projects[$i];
    		$photo = $photoMapper->getFirstPhoto($project->getIdProjet());
    		if($photo instanceof Application_Model_Photo) {
    			$project->addPhoto($photo);
    		}    		
    	}
    	$this->view->projects = $projects;
    	
    	// Affectation des valeurs à la vue
    	$this->view->title = $this->_getParam('title', 'Projets');
    	$this->view->display = $display;
    	$this->view->page = $page;
    	$this->view->limit = $limit;
    	$this->view->offset = $offset;
		$this->view->displayToolbar = $this->_getParam('displayToolbar', true);
    	$this->view->displayPages = $this->_getParam('displayPages', true);
    	$this->view->type = $type;
    	
    	// Javascript
    	$this->view->headScript()->appendFile('/js/default/project/list.js');
    }

    public function viewAction()
    {
    	$digitValidator = new Zend_Validate_Digits();
		
    	// Récuperation de l'idProjet
		$id = ($digitValidator->isValid($this->_getParam('id'))) ? $this->_getParam('id') : 0;
		
		// Récuperation du projet
		$projectMapper = new Application_Model_ProjectMapper();
		$project = $projectMapper->getProject($id);
		
		// Si aucun projet n'a été trouvé, on redirige vers la liste
		if(is_null($project)) $this->_helper->getHelper('Redirector')->gotoSimple('list');
		
		// Récuperation des photos
		$photoMapper = new Application_Model_PhotoMapper();
		$project->setPhotos($photoMapper->getPhotos($id));
		
		// Affectation du projet à la vue
		$this->view->project = $project;
		
		// Javascript
		// Insertion du slider seulement si le projet possède plus d'une photo
		if($project->getCountPhotos()>1)
		{
			$this->view->headScript()->appendFile('/js/default/project/view.js');
		}
    }

    public function mosaicAction()
    {
    	// Redirection vers la liste
       $this->_helper->getHelper('Redirector')->gotoSimple('list');
    }

    public function toolbarAction()
    {
		$this->_helper->getHelper('viewRenderer')->setNoRender();
    }

    public function pagesAction()
    {
		$this->_helper->getHelper('viewRenderer')->setNoRender();
    }
}