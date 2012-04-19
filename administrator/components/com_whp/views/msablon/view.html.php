<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whpViewmsablon extends JView
{
	function display($tpl = null)
	{
        JToolBarHelper::title(jtext::_("msablon"));
        JToolBarHelper::apply();
		JToolBarHelper::save();
        JToolBarHelper::cancel();
		$model = $this->getModel("msablon");	
		$this->assignRef('model', $model);
		$this->assignRef('allGroups', $model->xmlParser->getAllFormGroups() );				
		parent::display($tpl);
	}// function

}// class
?>