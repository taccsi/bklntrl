<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');
require_once("components/com_whp/models/kosar.php");

class whpViewbkfizetes extends JView
{

	function display($tpl = null){
		/*
		$model = $this->getModel("bkfizetes");	
		//$this->assignRef('allGroups', $model->xmlParser->getAllDataGroups() );
		$this->assignRef('allFormGroups', $model->xmlParser->getAllFormGroups() );
		$modelFelhasznalo = $this->getModel("felhasznalo");
		//$this->assignRef('felhasznalaAdatok', $modelFelhasznalo->xmlParserFelhasznalo->getAllDataGroups() );
		//$this->assignRef('userData', $model->getUserData() );
		$this->assignRef('bkfizetes', $model->bkfizetes );
		//$this->assignRef('orderData', $model->getOrderData() );
		*/
		$model = $this->getModel( "bkfizetes" );
		$task = JRequest::getVar( "task", "" );	
		if( method_exists($model, $task) ){
			$this->assignRef('cont_', $model->$task() );
		}else{
			die( jtext::_("NEM_ENGEDELYEZETT_MUVELET") );
		}
		parent::display($tpl);
	}// function

}// class
?>