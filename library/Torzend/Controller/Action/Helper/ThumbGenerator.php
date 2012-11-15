<?php
require_once(APPLICATION_PATH.'/../library/PhpThumb/ThumbLib.inc.php');

/**
 * 
 * Aide d'action permettant la génération de miniatures
 * @author cedric
 *
 */
class Torzend_Controller_Action_Helper_ThumbGenerator extends Zend_Controller_Action_Helper_Abstract {
	
	public function direct($source, $width, $height, $destination) {
		$this->generate($source, $width, $height, $destination);
	}
	
	public function generate($source, $width, $height, $destination) {
		// Création d'un objet PhpThumb
		$thumb = PhpThumbFactory::create($source);
		
		// Redimensionnement
		$thumb->adaptiveResize($width, $height);
		
		// Sauvegarde de l'image
		$thumb->save($destination);
	}
}
?>