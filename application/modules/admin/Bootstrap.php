<?php
class Admin_Bootstrap extends Zend_Application_Module_Bootstrap
{
	protected function _initRegisterPlugins() {
		// Récuperation du frontController
		$this->getApplication()->bootstrap('frontController');
		$frontController = $this->getApplication()->getResource('frontController');
		
		// Enregistrement des plugins
		$frontController->registerPlugin(new Admin_Plugin_Layout());
	}
}
?>