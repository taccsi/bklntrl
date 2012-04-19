<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whViewTetel extends JView
{
	function display($tpl = null)
	{
		$model = $this->getModel("tetel");	
		$this->assignRef('model', $model);
		$this->assignRef('allGroups', $model->xmlParser->getAllFormGroups() );				
		parent::display($tpl);
	}// function

}// class
?>