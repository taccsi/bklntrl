<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whpViewszuz extends JView
{
	function display($tpl = null)
	{
        JToolBarHelper::title(jtext::_("szuz"));
        JToolBarHelper::apply();
		JToolBarHelper::save();
        JToolBarHelper::cancel();
		$model = $this->getModel("szuz");	
		$this->assignRef('model', $model);
		$this->assignRef('allGroups', $model->xmlParser->getAllFormGroups() );				
		parent::display($tpl);
	}// function

}// class
?>