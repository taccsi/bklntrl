<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whViewmsablon extends JView
{
	function display($tpl = null)
	{
		$model = $this->getModel("msablon");	
		$this->assignRef('model', $model);
		$this->assignRef('id', $model->xmlParser->getAktVal("id") );	
		$this->assignRef('allGroups', $model->xmlParser->getAllFormGroups() );
		$this->assignRef('allDataGroups', $model->xmlParser->getAllDataGroups() );	
		//$this->assignRef('mezok', $model->getMezok() );	
		//$this->assignRef('belsoMenu', $model->getBelsoMenu() );

		//$this->assignRef('oneletrajzok', $this->get("Oneletrajzok"));				
		parent::display($tpl);
	}// function

}// class
?>