<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whpViewfelhasznalo extends JView
{
	function display($tpl = null)
	{
		$model = $this->getModel(); 
		$this->assignRef('allGroups', $model->xmlParser->getAllFormGroups() );			
		parent::display($tpl);
	}// function

}// class
?>