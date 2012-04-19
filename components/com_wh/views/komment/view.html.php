<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whViewkomment extends JView
{
	function display($tpl = null)
	{
		$model = $this->getModel("komment");	
		$this->assignRef('model', $model);
		//@$this->assignRef('komment', $model->getkomment((int)$_GET['cid'][0]));
		$this->assignRef('allGroups', $model->xmlParser->getAllFormGroups() );				
		$this->assignRef('allDataGroups', $model->xmlParser->getAllDataGroups() );	
			
		//$this->assignRef('oneletrajzok', $this->get("Oneletrajzok"));				
		parent::display($tpl);
	}// function

}// class
?>