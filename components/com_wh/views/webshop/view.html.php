<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whViewwebshop extends JView
{
	function display($tpl = null)
	{
		$model = $this->getModel("webshop");	
		$this->assignRef('model', $model);
		$this->assignRef('allGroups', $model->xmlParser->getAllFormGroups() );				
		$this->assignRef('allDataGroups', $model->xmlParser->getAllDataGroups() );	
		//$this->assignRef('oneletrajzok', $this->get("Oneletrajzok"));				
		parent::display($tpl);
	}// function

}// class
?>