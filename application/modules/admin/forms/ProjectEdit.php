<?php

class Admin_Form_ProjectEdit extends Admin_Form_ProjectCreate
{	
	public function init() {
		// Appel de la fonction parent
		parent::init();
		
		// Modification du formulaire
		$this->setAction('/admin/project/update');
		$this->getElement('submit')->setLabel('Editer');
		
		// ID Projet
		$idProjet = new Zend_Form_Element_Hidden('idProjet');
		
		// Ajout des nouveaux elements au formulaire
		$this->addElement($idProjet);
	}
	
	public function setProject(Application_Model_Project $project) {
		
		// ID Projet
		$this->getElement('idProjet')->setValue($project->getIdProjet());
		
		// Subform Project
		$projectForm = $this->getSubForm('project');
		$projectForm->getElement('idProjet')->setValue($project->getIdProjet());
		$projectForm->getElement('nom')->setValue($project->getNom());
		$projectForm->getElement('type')->setValue($project->getType());
		$projectForm->getElement('url')->setValue($project->getUrl());
		$projectForm->getElement('texte')->setValue($project->getTexte());
		$projectForm->getElement('date')->setValue($project->getDate());
	}
}
?>