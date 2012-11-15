<?php

class Admin_Form_AuthLogin extends Zend_Form
{

    public function init()
    {
    	// Parametrage du formulaire
        $this->setAction('/admin/auth/connect');
        $this->setMethod('post');
        
        // Login
        $login = new Zend_Form_Element_Text('login');
        $login->setLabel('Utilisateur');
        $login->setRequired(true);
        $login->setOptions(array('class' => 'inputText'));
        $login->addFilters(array(
        	'StringTrim',
        	new Zend_Filter_HtmlEntities(array(
				'quotestyle' => ENT_QUOTES
			)),
        ));
        
        // Password
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Mot de passe');
        $password->setRequired(true);
        $password->setOptions(array('class' => 'inputText'));
        $password->addFilters(array(
        	'StringTrim',
        	new Zend_Filter_HtmlEntities(array(
				'quotestyle' => ENT_QUOTES
			)),
        ));
        
        // Hash
        $hash = new Zend_Form_Element_Hash('hash');

        // Submit
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Connexion');
        
        // Ajout des éléments dans le formulaire
        $this->addElements(array(
        	$login,
        	$password,        	
        	$submit,
        	$hash,
        ));
    }
}
?>