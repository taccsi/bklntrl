<?php
defined('_JEXEC') or die('=;)');

jimport( 'joomla.application.component.view');
class whpViewRendeles extends JView
{
    function display($tpl = null)
    {
        $model = $this->getModel("rendeles");
		$task = jrequest::getvar("task");
		$this->assignRef('ajaxContent', $model->$task() );
			
		//$teszt = "**************** * * * ** *";
		//$this->assignRef('ajaxContent', $teszt );		
        $this->setLayout('raw');
        parent::display($tpl);
    }//function

}//class
