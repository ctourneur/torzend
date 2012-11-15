<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initAutoload() {
		Zend_Loader_Autoloader::getInstance()->registerNamespace('Torzend_');
	}
	
	protected function _initDoctype() {
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->getHelper('Doctype')->setDoctype('XHTML1_STRICT');
		
		return $view;
	}
	
	protected function _initDefaultLayout() {
		$this->bootstrap('layout');
		$layout = $this->getResource('layout');
		$layout->setLayoutPath(APPLICATION_PATH.'/modules/default/layouts');
		$layout->setLayout('default');
		
		return $layout;
	}
	
	protected function _initDbEncoding() {
		$this->bootstrap('db');
		$db = $this->getResource('db');
		
		// Modification de l'encodage de la base de données
		$db->query("SET NAMES 'utf8'");
		
		return $db;
	}
	
	protected function _initRegistry() {
		$this->bootstrap('db');
		$db = $this->getResource('db');
		
		// Stockage du DB Adapter dans le registre
		Zend_Registry::set('db', $db);
		
		return $db;
	}
	
	protected function _initCache() {
		// Création du cache
		$frontendOptions = array(
			'lifetime' => 345600,
			'automatic_seralization' => true
		);
		$backendOptions = array(
			'cache_dir' => APPLICATION_PATH.'/../www/tmp'
		);
		$cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
		
		// Affectation du cache
		Zend_Locale_Data::setCache($cache);
	}
	
	protected function _initRegisterActionsHelpers() {
		// ThumbGenerator
		$thumbGenerator = new Torzend_Controller_Action_Helper_ThumbGenerator();		
		Zend_Controller_Action_HelperBroker::addHelper($thumbGenerator);
	}
	
	protected function _initRegisterViewsHelpers() {
		// Récuperation de la vue
		$this->bootstrap('view');
		$view = $this->getResource('view');
		
		// Enregistrement des aides de vues
		$view->registerHelper(new Torzend_View_Helper_Pages(), 'pages');
		$view->registerHelper(new Torzend_View_Helper_Paginator(), 'paginator');
	}
	
	protected function _initNavigation() {
		// Pages
		$pages = array(
			array(
				'label' => 'Accueil',
				'title' => 'Récents projets réalisés en PHP, Javascript',
				'uri' => '/',
				'class' => 'menuElement',
				'pages' => array(
					array(
						'label' => 'Projets',
						'title' => 'Liste des projets PHP, Javascript',
						'uri' => '/project/list',
						'class' => 'menuElement'
					),
					array(
						'label' => 'Derrière le site',
						'title' => 'Liste des composants Zend Framework utilisés',
						'uri' => '/index/about',
						'class' => 'menuElement'				
					),
					array(
						'label' => 'Contact',
						'title' => 'Contact',
						'uri' => '/index/contact',
						'class' => 'menuElement'
					),
					array(
						'label' => 'Plan du site',
						'title' => 'Plan du site',
						'uri' => '/index/sitemap',
						'visible' => false
					)
				)
			),
		);
		
		// Navigation
		$navigation = new Zend_Navigation($pages);
		
		// Ajout des projets
		$projectListPage = $navigation->findOneBy('uri', '/project/list');
		if(!is_null($projectListPage))
		{
			$projectMapper = new Application_Model_ProjectMapper();
			$projects = $projectMapper->getAllProjects();
			if(count($projects)>0)
			{
				foreach($projects as $project)
				{
					$projectListPage->addPage(new Zend_Navigation_Page_Uri(array(
						'label' => $project->getNom(),
						'title' => $project->getNom(),
						'uri' => '/project/view/id/'.$project->getIdProjet()
					)));
				}
			}
		}
		
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->navigation($navigation); 
	}
}
?>