<?php
class Default_Bootstrap extends Zend_Application_Module_Bootstrap
{
	protected function _initRegisterPlugins() {
		// Récuperation du frontController
		$this->bootstrap('frontController');
		$frontController = $this->getResource('frontController');
		
		// Enregistrement des plugins
		$frontController->registerPlugin(new Default_Plugin_Layout());
		$frontController->registerPlugin(new Default_Plugin_Theme());
	}
}
?>