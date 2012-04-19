<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whViewbeszallito extends JView
{
	function display($tpl = null)
	{
		$model = $this->getModel("beszallito");	
		$this->assignRef('model', $model);
		$this->assignRef('allGroups', $model->xmlParser->getAllFormGroups() );				
		//$this->assignRef('allDataGroups', $model->xmlParser->getAllDataGroups() );	
		parent::display($tpl);
	}// function

}// class
?>