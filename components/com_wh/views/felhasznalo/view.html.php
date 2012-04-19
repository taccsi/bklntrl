<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whViewfelhasznalo extends JView
{
	function display($tpl = null)
	{
		$model = $this->getModel(); 
		//$this->assignRef('allGroups', $model->xmlParser->getAllFormGroups() );
		$this->assignRef('f', $model->getVasarlo(jrequest::getVar("user_id", 0), jrequest::getVar("webshop_id", 0) ) );
		$this->assignRef('vasarlasok', $model->getVasarlasok() );
		parent::display($tpl);
	}// function

}// class
?>