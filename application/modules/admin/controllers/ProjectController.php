<?php

class Admin_ProjectController extends Zend_Controller_Action
{
	protected $_redirector;
	protected $_flashMessenger;
	
	public function preDispatch() {
		// On vérifie si l'utilisateur s'est authentifié
		if(!Zend_Auth::getInstance()->hasIdentity())
		{
			// Redirection vers la page d'authentification
			$this->_helper->getHelper('Redirector')->gotoSimple('login', 'auth', 'admin');
		}
	}
	
    public function init()
    {
    	// Récuperation de l'aide d'action Redirector
        $this->_redirector = $this->_helper->getHelper('Redirector');
        
        // Récuperation de l'aide d'action FlashMessenger
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

    public function indexAction()
    {
        $this->_forward('list');
    }

    public function listAction()
    {
        // Instanciation des mappers
        $projectMapper = new Application_Model_ProjectMapper();
        $photoMapper = new Application_Model_PhotoMapper();
    	
    	// Paramètres
    	$filters = array();    	
        $limit = 20;
        $pageCurrent = (is_numeric($this->_getParam('page'))) ? $this->_getParam('page') : 1;
        
        // Calcul de l'offset
        $offset = ($pageCurrent-1)*$limit;
        
        // Récuperation de la liste des projets 
        $projects = $projectMapper->getProjects($filters, $offset, $limit, 'idProjet', 'ASC');
        if(count($projects)>0)
        {
	        foreach($projects as $project)
	        {
	        	// Settings des photos
	        	$project->setPhotos($photoMapper->getPhotos($project->getIdProjet()));
	        }
        }
        
        // Récuperation du nombre de projets
        $count = $projectMapper->count($filters);
        
        // Calcul du nombre de pages
        $this->view->pageCount = ceil($count/$limit);
        
        // Assignation des paramètres
        $this->view->offset = $offset;
        $this->view->pageCurrent = $pageCurrent;
        
        // Assignation des projets
        $this->view->projects = $projects;
        
        // Assignation du nombre de projets
        $this->view->count = $count;
        
        // Assignation des messages
        $this->view->messages = $this->_flashMessenger->getMessages();
        
        // Assignation des actions
        $this->view->actions = array(
        	array(
        		'label' => 'Créer',
	        	'url' => $this->view->url(array(
        			'module' => 'admin',
	        		'controller' => 'project',
	        		'action' => 'create'
	        	), null, true)
        	)
        );
		
		// Javascript
		$this->view->headScript()->appendFile('/js/admin/list.js');
    }

    public function editAction()
    {
    	// Paramètres
    	$id = (is_numeric($this->_getParam('id'))) ? $this->_getParam('id') : 0;
    	
		// Instanciation du mapper
		$projectMapper = new Application_Model_ProjectMapper();
		
		// Récuperation du projet
		$project = $projectMapper->getProject($id);
		
		if(is_null($project))
		{			
			// Redirection
			$this->_redirector->gotoSimple('create');
		}
		
		$formSession = new Zend_Session_Namespace('Torzend_Form');
		
		// Formulaire de mise à jour du projet
		if(isset($formSession->adminProjectEdit))
		{
			$editProjectForm = $formSession->adminProjectEdit;
			$formSession->unsetAll();
		}
		else
		{
			$editProjectForm = new Admin_Form_ProjectEdit();
			$editProjectForm->setProject($project);	
		}
		
		// Formulaire d'ajout d'une photo
		if(isset($formSession->adminPhotoCreate))
		{
			$createPhotoForm = $formSession->adminPhotoCreate;
			$formSession->unsetAll();
		}
		else
		{
			$createPhotoForm = new Admin_Form_PhotoCreate($project->getIdProjet());
		}
		
		$this->view->id = $id;
		$this->view->editProjectForm = $editProjectForm;
		$this->view->createPhotoForm = $createPhotoForm;
		
		// Récuperation des eventuels messages
		$this->view->messages = $this->_flashMessenger->getMessages();
    }

    public function createAction()
    {
    	$formSession = new Zend_Session_Namespace('Torzend_Form');
    	
    	if(isset($formSession->adminProjectCreate)) {
    		$form = $formSession->adminProjectCreate;
    		$formSession->unsetAll();
    	}
    	else
    	{
    		$form = new Admin_Form_ProjectCreate();	
    	}
    			
		// Affectation du formulaire à la vue
		$this->view->form = $form;
    }

    public function insertAction()
    {
    	// Désactivation de la vue
    	$this->_helper->getHelper('ViewRenderer')->setNoRender(true);
    	
    	// Instanciation du formulaire
    	$form = new Admin_Form_ProjectCreate();
    	
		if($this->getRequest()->isPost())
		{
			if($form->isValid($this->getRequest()->getPost()))
			{
				// Traitement de la date
				$pregReplaceFilter = new Zend_Filter_PregReplace(
					array(
						'match' => '#^([0-9]{2})/([0-9]{2})/([0-9]{4})$#',
						'replace' => '$3-$2-$1'
					)
				);
				$date = $pregReplaceFilter->filter($form->getSubForm('project')->getValue('date'));
				
				// Insertion du project
				$projectMapper = new Application_Model_ProjectMapper();
				$rows = $projectMapper->createProject(array_merge($form->getSubform('project')->getValues(true), array('date' => $date)));
				$lastInsertId = $projectMapper->getLastInsertId();
				
				// Ajout du message
				$this->_helper->getHelper('FlashMessenger')->addMessage('Projet inseré avec succès');
				
				// Redirection
				$url = '/admin/project/edit/id/'.$lastInsertId;
				$this->_helper->getHelper('Redirector')->gotoUrl($url);
			}
			else
			{
				$formSession = new Zend_Session_Namespace('Torzend_Form');
				$formSession->adminProjectCreate = $form;
				
				// Redirection
				$this->_helper->getHelper('Redirector')->gotoSimple('create');
			}
		}
    }

    public function updateAction()
    {
    	// Désactivation de la vue
    	$this->_helper->getHelper('ViewRenderer')->setNoRender(true);
    	
    	// Instanciation du formulaire
    	$form = new Admin_Form_ProjectEdit();
    	
    	if($this->getRequest()->isPost())
    	{
    		if($form->isValid($this->getRequest()->getPost()))
    		{
    			// Traitement de la date
    			$pregReplaceFilter = new Zend_Filter_PregReplace(
    				array(
						'match' => '#^([0-9]{2})/([0-9]{2})/([0-9]{4})$#',
						'replace' => '$3-$2-$1'
    				)
    			);
    			$date = $pregReplaceFilter->filter($form->getSubForm('project')->getValue('date'));
    			
    			// ID Projet
    			$idProjet = $form->getValue('idProjet');
    			
    			// Mise à jour du projet
    			$projectMapper = new Application_Model_ProjectMapper();
    			$projectMapper->setProject($idProjet, array_merge($form->getSubForm('project')->getValues(true), array('date' => $date)));
    			
    			// Message
    			$message = 'Mise à jour du projet réalisé avec succès';
    			$this->_helper->getHelper('FlashMessenger')->addMessage($message);
    			
    			// Redirection vers la liste
    			$this->_helper->getHelper('Redirector')->gotoSimple('list');
    		}
    		else
    		{
    			// Formulaire non valide, mise en session pour afficher les erreurs
    			$formSession = new Zend_Session_Namespace('Torzend_Form');
    			$formSession->editProjectForm = $form;
    			
    			// Redirection vers le projet
    			$idProjet = $form->getValue('idProjet');
    			$this->_helper->getHelper('Redirector')->gotoSimple('edit', 'project', 'admin', array('id' => $idProjet));
    		}
    	}
    	
    	// Redirection
    	$this->_forward('list');
    }

    public function deleteAction()
    {
    	// Désactivation de la vue
    	$this->_helper->getHelper('ViewRenderer')->setNoRender(true);
    	
		// Récuperation de l'ID projet à supprimer
		$id = (is_numeric($this->_getParam('id'))) ? $this->_getParam('id') : null;
		
		if(!is_null($id)) {
    		// Suppression du projet
    		$projectMapper = new Application_Model_ProjectMapper();
    		$deletedRows = $projectMapper->deleteProject($id);
    		
    		// Message
			$message = $deletedRows.' projet(s) supprimé(s)';
    		$this->_helper->getHelper('FlashMessenger')->addMessage($message); 
		}
    	
		// Redirection
		$this->_helper->getHelper('Redirector')->gotoSimple('list');
    }
}

