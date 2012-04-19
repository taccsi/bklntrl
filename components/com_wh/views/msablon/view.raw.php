<?php
defined('_JEXEC') or die('=;)');

jimport( 'joomla.application.component.view');
class whViewMsablon extends JView
{ 
    /**
     * xxx view display method
     * @return void
     **/
    function display($tpl = null)
    {
		//die("gkgkdfégkdgék");
        $model = $this->getModel();
		$task = jrequest::getvar("task");
		$this->assignRef('ajaxContent', $model->$task() );		
        $this->setLayout('raw');
        parent::display($tpl);
    }//function

}//class
?> 
