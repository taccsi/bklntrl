<?php
defined('_JEXEC') or die('=;)');

jimport( 'joomla.application.component.view');
class whpViewbkfizetes extends JView
{
    function display($tpl = null)
    {
        $model = $this->getModel("bkfizetes");
		$task = jrequest::getvar("task");
		$this->assignRef('ajaxContent', $model->$task() );
			
		//$teszt = "**************** * * * ** *";
		//$this->assignRef('ajaxContent', $teszt );		
        $this->setLayout('raw');
        parent::display($tpl);
    }//function

}//class
