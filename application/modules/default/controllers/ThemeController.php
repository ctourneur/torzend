<?php

class Default_ThemeController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function listAction()
    {
    	// Récuperation de la liste des thèmes
     	$themeMapper = new Application_Model_ThemeMapper();
     	$this->view->themes = $themeMapper->getThemes();
    }

    public function changeAction()
    {
    	// Validator
    	$digitsValidator = new Zend_Validate_Digits();
    	
    	// Mapper
    	$themeMapper = new Application_Model_ThemeMapper();
    	
    	// Récuperation de l'id
    	$id = ($digitsValidator->isValid($this->_getParam('id'))) ? $this->_getParam('id') : 0;
    	
    	// Récuperation du theme
    	$theme = $themeMapper->getTheme($id);
    	
    	// Récuperation du layout
    	$layout = ($theme instanceof Application_Model_Theme) ? $theme->getLayout() : $this->_helper->getHelper('Layout')->getLayout();
    	
    	// Enregistrement du layout en session
    	$themeSession = new Zend_Session_Namespace('Torzend_Theme');
    	$themeSession->layout = $layout;
    	
    	// Redirection vers l'URL stockée
    	$this->_helper->getHelper('Redirector')->gotoUrl($themeSession->url);
    	
    	// Désactivation du rendu automatique
		$this->_helper->getHelper('ViewRenderer')->setNoRender(true);
    }
}





