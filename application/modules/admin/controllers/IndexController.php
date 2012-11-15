<?php
class Admin_IndexController extends Zend_Controller_Action
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
        $this->_forward('list', 'project', 'admin');
    }

    public function sliderAction()
    {
        // action body
    }


}

?>
