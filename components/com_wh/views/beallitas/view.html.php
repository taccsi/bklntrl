<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whViewbeallitas extends JView
{
	function display($tpl = null)
	{
		$model = $this->getModel("beallitas"); 
		$this->assignRef('allGroups', $model->xmlParser->getAllFormGroups() );
		$this->assignRef('aktiv_pane_id', $model->getSessionVar("aktiv_pane_id") );	
		//$this->assignRef('allDataGroups', $model->xmlParser->getAllDataGroups() );	
		parent::display($tpl);
	}// function

}// class
?>