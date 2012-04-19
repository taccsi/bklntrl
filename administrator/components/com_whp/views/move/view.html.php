<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whpViewmove extends JView
{
	function display($tpl = null)
	{
		//$this->printLink();
		$model = $this->getModel("move"); 
		//$this->assignRef('allGroups', $model->getAllDataGroups() );	
		parent::display($tpl);
	}// function

}// class
?>