<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whViewtermek extends JView
{
	function display($tpl = null)
	{
		$model = $this->getModel(); 
		//echo "---<br />";
		$this->assignRef('lang_forms', $model->getLangForms() );
		$this->assignRef('allGroups', $model->xmlParser->getAllFormGroups() );
		$this->assignRef('aktiv_pane_id', $model->getSessionVar("aktiv_pane_id") );
		$this->assignRef('cimkeForm', $model->getCimkeForm() );	

		//$this->assignRef('allDataGroups', $model->xmlParser->getAllDataGroups() );	
		parent::display($tpl);
	}// function

}// class