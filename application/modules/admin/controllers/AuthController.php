<?php

class Admin_AuthController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		$this->_forward('login');
    }

    public function loginAction()
    {
        // On vérifie si l'utilisateur n'est pas déjà connecté
    	if(Zend_Auth::getInstance()->hasIdentity())
    	{
    		// Redirection vers la liste des projets
    		$this->_helper->getHelper('Redirector')->gotoSimple('list', 'project', 'admin');
    	}
    	
    	// Modification du layout
    	$this->_helper->getHelper('Layout')->setLayout('authentification');
    	
		// Récuperation des messages
		$this->view->messages = $this->_helper->getHelper('FlashMessenger')->getMessages();

		// Instanciation du formulaire
		$this->view->form = new Admin_Form_AuthLogin();
    }

    public function connectAction()
    {    	
    	// On vérifie si l'utilisateur n'est pas déjà connecté
    	if(Zend_Auth::getInstance()->hasIdentity())
    	{
    		// Redirection vers la liste des projets
    		$this->_helper->getHelper('Redirector')->gotoSimple('list', 'project', 'admin');
    	}
    	
		// Desactivation de la vue
    	$this->_helper->getHelper('ViewRenderer')->setNoRender(true);
    	
        // Tentative de connexion
        $form = new Admin_Form_AuthLogin();
        if($this->getRequest()->isPost())
        {
        	if($form->isValid($this->getRequest()->getPost()))
        	{
        		// Configuration de l'AuthDapter
        		$authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get('db'));
        		$authAdapter->setTableName('utilisateurs')
        					->setIdentityColumn('login')
        					->setCredentialColumn('password');
        					
        		// Affectation des valeurs
        		$authAdapter->setIdentity($form->getValue('login'));
        		$authAdapter->setCredential($form->getValue('password'));
        		$authResult = $authAdapter->authenticate();
        		
        		if($authResult->isValid())
        		{
    				// Stockage de l'utilisateur dans Zend_Auth
    				$storage = Zend_Auth::getInstance()->getStorage();
    				$storage->write($authAdapter->getResultRowObject(null, 'password'));
    				
    				// Redirection vers la liste des projets
			        $this->_helper->getHelper('Redirector')->gotoSimple('list', 'project', 'admin');
        		}
        		else
        		{
        			// Ajout du message
        			$this->_helper->getHelper('FlashMessenger')->addMessage('Login ou mot de passe incorrect');
        		}
        	}
        }
        
        // Redirection vers la page de login
        $this->_helper->getHelper('Redirector')->gotoSimple('login', 'auth', 'admin');
    }

    public function disconnectAction()
    {
        // Déconnexion de l'utilisateur
        Zend_Auth::getInstance()->clearIdentity();
        
        // Ajout du message
        $this->_helper->getHelper('FlashMessenger')->addMessage('Vous vous êtes déconnecté');
        
        // Desactivation de la vue
        $this->_helper->getHelper('ViewRenderer')->setNoRender(true);
        
        // Redirection
        $this->_helper->getHelper('Redirector')->gotoSimple('login', 'auth', 'admin');
    }
}
?>