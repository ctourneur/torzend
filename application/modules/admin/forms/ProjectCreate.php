<?php

class Admin_Form_ProjectCreate extends Zend_Form
{
    public function init()
    {    	
    	// Initialisation du formulaire
        $this->setAction('/admin/project/insert');
        $this->setMethod('post');
        
        // Decorators
        $elementDecorators = array(
        	'ViewHelper',
        	'Errors',
        	array('HtmlTag', array('tag' => 'dd')),
        	array('Label', array('tag' => 'dt')),
        );
        
        $submitDecorators = array(
        	'ViewHelper',
        	array('HtmlTag', array('tag' => 'dd'))
        );
        
        $fieldsetDecorators = array(
        	'ViewHelper'
        );
        
        $formDecorators = array(
        	'ViewHelper'
        );
        
        // Ajout des éléments dans le subform
		$project = new Zend_Form_SubForm();
		$project->setLegend('Projet');
        $project->addElements(array(
			new Zend_Form_Element_Text('idProjet', array(
				'label' => 'ID Projet',
				'filters' => array(
					'StringTrim'
	        	),
	        	'decorators' => $elementDecorators,
	        	'class' => 'inputText'
			)),
			new Zend_Form_Element_Text('nom', array(
				'required' => true,
				'label' => 'Nom',
				'filters' => array(
					'StringTrim'
				),
				'decorators' => $elementDecorators,
				'class' => 'inputText'
			)),
			new Zend_Form_Element_Text('type', array(
				'required' => true,
				'label' => 'Type de site',
				'filters' => array(
					'StringTrim'
				),
				'decorators' => $elementDecorators,
				'attribs' => array('class' => 'inputText')
			)),
			new Zend_Form_Element_Text('url', array(
				'required' => true,
				'label' => 'URL',
				'filters' => array(
					'StringTrim'
				),
				'decorators' => $elementDecorators,
				'attribs' => array('class' => 'inputText')
			)),
			new Zend_Form_Element_Textarea('texte', array(
				'label' => 'Texte',
				'filters' => array(
					'StringTrim'
	        	),
				'attribs' => array(
					'class' => 'mceEditor'
				),
				'decorators' => $elementDecorators
			)),
			new Zend_Form_Element('date', array(
				'required' => true,
				'label' => 'Date',
				'filters' => array(
					'StringTrim'
	        	),
				'validators' => array(
					new Zend_Validate_Date(array(
						'format' => 'dd/MM/yyyy'
					))
	        	),
	        	'decorators' => $elementDecorators,
	        	'class' => 'inputText'
	        ))
        ));
        
        // Ajout des Subforms
        $this->addSubForm($project, 'project');
        
        // Ajout des elements au formulaire
        $this->addElements(array(
			new Zend_Form_Element_Submit('submit', array(
				'label' => 'Créer',
				'decorators' => $submitDecorators
			))
        ));
    }
}
?>