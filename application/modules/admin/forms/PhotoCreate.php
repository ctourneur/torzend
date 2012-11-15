<?php

class Admin_Form_PhotoCreate extends Zend_Form
{
	protected $_id;
	
	public function __construct($id=null, $options=null) {
		// Récuperation de l'ID Projet
		$this->_id = $id;
		
		// Appel du constructeur parent
		parent::__construct($options);
	}
	
    public function init()
    {
        // Initialisation du formulaire
        $this->setAction('/admin/photo/insert');
        $this->setMethod('post');
        $this->setAttrib('enctype', 'multipart/form-data');
        
        // ID Projet
        $idProjet = new Zend_Form_Element_Hidden('idProjet');
        $idProjet->setValue($this->_id);
        $idProjet->setRequired(true);
        
        // Titre
        $titre = new Zend_Form_Element_Text('titre');
        $titre->setLabel('Titre');
        $titre->setOptions(array('class' => 'inputText'));
        $titre->setRequired(true);
        
        // Description
        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Description');
        $description->setRequired(true);
        
        // Photo
        $photo = new Zend_Form_Element_File('photo');
        $photo->setLabel('Photo');
        
        // Submit
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Valider');
        
        // Ajout des élements au formulaire
        $this->addElements(array(
        	$idProjet,
        	$titre,
        	$description,
        	$photo,
        	$submit
        ));
    }
}
?>