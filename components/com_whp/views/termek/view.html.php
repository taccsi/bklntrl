<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whpViewtermek extends JView
{
	function display($tpl = null)
	{ 
	
		$model = $this->getModel("termek"); 
		$this->assignRef('termek', $model->gettermek() );
		$this->assignRef('foglalas', $model->getfoglalas() );
		
		//$this->assignRef('eok_vasarlas_szoveg', $model->geteokvasarlas_szoveg() );
		//$this->assignRef('allDataGroups', $model->xmlParser->getAllDataGroups() );	
		parent::display($tpl);
	}// function

}// class
?>