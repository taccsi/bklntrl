<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');
require_once("components/com_whp/models/kosar.php");

class whpViewrendeles extends JView
{

	function display($tpl = null)
	{
	//echo'fsdffdsfs';
		if (jrequest::getvar('layout')!='thankyou'){
			$model = $this->getModel("rendeles");	
			//$this->assignRef('allGroups', $model->xmlParser->getAllDataGroups() );
			$this->assignRef('allFormGroups', $model->xmlParser->getAllFormGroups() );				
			//$modelFelhasznalo = $this->getModel("felhasznalo");
			//$this->assignRef('felhasznalaAdatok', $modelFelhasznalo->xmlParserFelhasznalo->getAllDataGroups() );								
			//$this->assignRef('userData', $model->getUserData() );
			$this->assignRef('rendeles', $model->rendeles );	
			}
				
			//$this->assignRef('orderData', $model->getOrderData() );		
		parent::display($tpl);
	}// function

}// class
?>