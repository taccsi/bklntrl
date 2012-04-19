<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whpViewatvevohely extends JView
{
	function display($tpl = null)
	{ 
	
		$model = $this->getModel("atvevohely"); 
		$this->assignRef('atvevohely', $model->getatvevohely() );
		//$this->assignRef('allDataGroups', $model->xmlParser->getAllDataGroups() );	
		parent::display($tpl);
	}// function

}// class
?>