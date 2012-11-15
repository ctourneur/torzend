<?php
class Admin_Plugin_Layout extends Zend_Controller_Plugin_Abstract {
	
	public function dispatchLoopStartup($request) {
		if('admin' == $request->getModuleName())
		{
			Zend_Layout::getMvcInstance()->setLayoutPath(APPLICATION_PATH.'/modules/admin/layouts/')
										 ->setLayout('admin');
		}
	}
}
?>