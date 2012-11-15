<?php
class Default_Plugin_Layout extends Zend_Controller_Plugin_Abstract {
	
	public function dispatchLoopStartup($request) {
		if('default' == $request->getModuleName())
		{
			// Récuperation du layout
			$themeSession = new Zend_Session_Namespace('Torzend_Theme');
			$layout = (isset($themeSession->layout)) ? $themeSession->layout : 'default';
			
			Zend_Layout::getMvcInstance()->setLayoutPath(APPLICATION_PATH.'/modules/default/layouts/')
										 ->setLayout($layout);			
		}
	}
}
?>