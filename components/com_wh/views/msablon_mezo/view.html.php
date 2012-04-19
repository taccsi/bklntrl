<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whViewmsablon_mezo extends JView
{
	function display($tpl = null)
	{
		$model = $this->getModel("msablon_mezo");	
		$this->assignRef('model', $model);
		@$this->assignRef('msablon_mezo', $model->getmsablon_mezo((int)$_GET['cid'][0]));
		$this->assignRef('allGroups', $model->xmlParser->getAllFormGroups() );				
		$this->assignRef('allDataGroups', $model->xmlParser->getAllDataGroups() );	
		
		
		$this->assignRef('belsoMenu', $model->getBelsoMenu() );		


		//$this->assignRef('oneletrajzok', $this->get("Oneletrajzok"));				
		parent::display($tpl);
	}// function

}// class
?>