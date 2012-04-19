<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whViewkategoria extends JView
{
	function display($tpl = null)
	{
		$model = $this->getModel(); 
		$this->assignRef('allGroups', $model->xmlParser->getAllFormGroups() );
		$this->assignRef('lang_forms', $model->getLangForms() );
		//$this->assignRef('images', $model->getImages() );
		//$this->assignRef('allDataGroups', $model->xmlParser->getAllDataGroups() );	
		parent::display($tpl);
	}// function

}// class
?>