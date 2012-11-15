<?php

class Admin_PhotoController extends Zend_Controller_Action
{
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
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function insertAction()
    {
    	// Désactivation de la vue
        $this->_helper->getHelper('ViewRenderer')->setNoRender(true);
        
        // Récuperation du formulaire
        $form = new Admin_Form_PhotoCreate();
		
		if($this->getRequest()->isPost())
		{
			if($form->isValid($this->getRequest()->getPost()))
			{				
				// Récuperation du nom du fichier
				$source = $form->photo->getFileName();
				
				// Récuperation de l'extension du fichier
				$extension = pathinfo($source, PATHINFO_EXTENSION);
				$filename = pathinfo($source, PATHINFO_FILENAME);
				
				// Création du nom fichier final
				$basename = basename($source);
				$destination = APPLICATION_PATH.'/../www/docs/'.$basename;
				
				// Filter
				$form->photo->addFilter('Rename', $destination);
				
				// Upload
				$form->photo->receive();
				
				// Enregistrement à inserer
				$data = array(
					'idProjet' => $form->getValue('idProjet'),
					'src' => $basename,
					'titre' => $form->getValue('titre'),
					'description' => $form->getValue('description'),
				);
				
				// Inertion de la photo en base de données
				$photoMapper = new Application_Model_PhotoMapper();
				$photoMapper->insertPhoto($data);
				
				// Génération des miniatures
				$this->_helper->thumbGenerator($destination, 200, 100, dirname($destination).'/'.$filename.'_200x100.'.$extension);
				
				// Message
				$message = 'Photo inserée dans le projet';
				$this->_helper->getHelper('FlashMessenger')->addMessage($message);
			}
			else
			{
				// Formulaire non valide, mise en session pour afficher les erreurs
				$formSession = new Zend_Session_Namespace('Torzend_Form');
				$formSession->createPhotoForm = $form;
			}
			
			// Redirection
			$this->_helper->getHelper('Redirector')->gotoSimple('edit', 'project', 'admin', array('id' => $form->getValue('idProjet')));
		}
		
		// Redirection vers la liste des projets
		$this->_forward('list', 'project', 'admin');
    }

    public function listAction()
    {
    	// Récuperation de l'ID projet
    	$id = (is_numeric($this->_getParam('id'))) ? $this->_getParam('id') : 0;
    	
    	// Récuperation de la liste des photos
		$photoMapper = new Application_Model_PhotoMapper();
		$photos = $photoMapper->getPhotos($id);
		
		$this->view->photos = $photos;
    }

    public function deleteAction()
    {
    	// Desactivation de la vue
    	$this->_helper->getHelper('ViewRenderer')->setNoRender(true);
    	
    	// Récuperation de l'ID photo
		$id = (is_numeric($this->_getParam('id'))) ? $this->_getParam('id') : 0;
		
		// Récuperation de la photo
		$photoMapper = new Application_Model_PhotoMapper();
		$photo = $photoMapper->getPhoto($id);
		
		if(!is_null($photo))
		{
			// Suppression de la photo
			$rowsDeleted = $photoMapper->deletePhoto($id);
		
			// Message
			$message = $rowsDeleted.' photo supprimée';
			$this->_helper->getHelper('FlashMessenger')->addMessage($message);
			
			// Redirection
			$this->_helper->getHelper('Redirector')->gotoSimple('edit', 'project', 'admin', array('id' => $photo->getIdProjet()));
		}
		else
		{
			$this->_helper->getHelper('Redirector')->gotoSimple('list', 'project', 'admin');
		}
    }
}