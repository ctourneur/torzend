<?php

class Default_IndexController extends Zend_Controller_Action
{

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
    }

    public function indexAction()
    {
    	// Slider
    	$content = $this->view->action('slider', 'index', null);
		$this->getResponse()->append('slider', $content);
		
		// Récuperation des derniers projets
		$projectMapper = new Application_Model_ProjectMapper();
		$projects = $projectMapper->getProjects(array(), 0, 6, 'date', 'DESC');
		
		// Récuperation des miniatures
		$photoMapper = new Application_Model_PhotoMapper();
		for($i=0; $i<count($projects); $i++)
		{
			$project = $projects[$i];
			
			// Récuperation de la première photo et insertion dans le projet
			$photo = $photoMapper->getFirstPhoto($project->getIdProjet());
			if($photo instanceof Application_Model_Photo) {				
				$project->addPhoto($photo);
			}
		}
		
		$this->view->projects = $projects;
		$this->view->noBreadcrumbs = true;
    }

    public function aboutAction()
    {
        // action body
    }

    public function linksAction()
    {
        // action body
    }

    public function contactAction()
    {
        // action body
    }

    public function sitemapAction()
    {
    	
    }

    public function sliderAction()
    {
    	// Récuperation des 5 derniers projets
    	$projectMapper = new Application_Model_ProjectMapper();
    	$projects = $projectMapper->getProjects(array(), 0, 3, 'date', 'DESC');
    	
    	// Récuperation des photos
    	if(count($projects)>0)
    	{
    		$photoMapper = new Application_Model_PhotoMapper();
    		foreach($projects as $project)
    		{
    			$project->addPhoto($photoMapper->getFirstPhoto($project->getIdProjet()));
    		}
    	}
    	$this->view->projects = $projects;
    	
    	// Javascript
    	$this->view->headScript()->appendFile('/js/default/index/slider.js');
    }

    public function referenceAction()
    {
    	switch($this->_getParam('id', 1))
    	{
    		case '1':
    			$offset = 0;
    			$limit = 98;
    			break;
    		case '2':
    			$offset = 99;
    			$limit = 54;
    			break;
    		case '3':
    			$offset = 154;
    			$limit = 9;
    			break;
    		case '4':
    			$offset = 163;
    			$limit = 76;
    			break;
    		case '5':
    			$offset = 240;
    			$limit = 80;
    			break;
    		case '6':
    			$offset = 321;
    			$limit = 13;
    			break;
    		case '7':
    			$offset = 334;
    			$limit = 21;
    			break;
    		case '8':
    			$offset = 356;
    			$limit = 33;
    			break;
    		case '9':
    			$offset = 390;
    			$limit = 32;
    			break;
    		case '10':
    			$offset = 423;
    			$limit = 10;
    			break;
    	}
    	// Récuperation de la liste des fonctions pour la certification
    	$certifMapper = new Application_Model_CertificationMapper();
    	$this->view->references = $certifMapper->getCertifications($offset, $limit);
    }
}


?>
