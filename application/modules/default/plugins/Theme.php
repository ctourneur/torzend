<?php
class Default_Plugin_Theme extends Zend_Controller_Plugin_Abstract {
	public function routeShutdown($request) {
		// Récuperation de l'espace de nom Torzend_Theme
		$themeSession = new Zend_Session_Namespace('Torzend_Theme');
		
		// Enregistrement de l'URL en session
		// Le controller doit être différent de theme
		if($request->getControllerName() != 'theme')
		{
			$themeSession->url = $request->getRequestUri();
		}
	}
}
?>