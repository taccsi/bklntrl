<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whViewkep extends JView
{
	function display($tpl = null)
	{
		$model = $this->getModel(); 
		$this->assignRef('allGroups', $model->xmlParser->getAllFormGroups() );				
		$this->assignRef('images', $model->getImages() );	
		parent::display($tpl);
	}// function

}// class
?>