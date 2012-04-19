<?php
defined('_JEXEC') or die('=;)');

jimport( 'joomla.application.component.view');
class whViewAtvhely extends JView
{ 
    /**
     * xxx view display method
     * @return void
     **/
    function display($tpl = null)
    {
        $model = $this->getModel();
		$task = jrequest::getvar("task");
		$this->assignRef('ajaxContent', $model->$task() );		
        $this->setLayout('raw');
        parent::display($tpl);
    }//function

}//class
?> 
