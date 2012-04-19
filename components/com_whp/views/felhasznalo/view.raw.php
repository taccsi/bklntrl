<?php
defined('_JEXEC') or die('=;)');

jimport( 'joomla.application.component.view');
class whpViewFelhasznalo extends JView
{
    function display($tpl = null)
    {
        $model = $this->getModel("felhasznalo");
		//$task = jrequest::getvar("task");
		
		$this->assignRef('ajaxContent', $model->ajaxCheck( jrequest::getvar("mandatoryFunc", "") ) );		
		//$teszt = "**************** * * * ** *";
		//$this->assignRef('ajaxContent', $teszt );		
        $this->setLayout('raw');
        parent::display($tpl);
    }//function

}//class
