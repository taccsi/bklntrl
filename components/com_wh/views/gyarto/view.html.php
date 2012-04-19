<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whViewgyarto extends JView
{
	function display($tpl = null)
	{
		$model = $this->getModel("gyarto");	
		$this->assignRef('model', $model);
		//@$this->assignRef('gyarto', $model->getgyarto((int)$_GET['cid'][0]));
		$this->assignRef('allGroups', $model->xmlParser->getAllFormGroups() );				
		$this->assignRef('allDataGroups', $model->xmlParser->getAllDataGroups() );	
		$this->assignRef('images', $model->getImages() );		
		//$this->assignRef('oneletrajzok', $this->get("Oneletrajzok"));				
		parent::display($tpl);
	}// function

}// class
?>