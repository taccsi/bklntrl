<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whpViewrendeles extends JView
{
	function display($tpl = null)
	{
        JToolBarHelper::title(jtext::_("rendeles"));
        JToolBarHelper::apply();
		JToolBarHelper::save();
        JToolBarHelper::cancel();
		$model = $this->getModel("rendeles");	
		$this->assignRef('model', $model);
		$this->assignRef('allGroups', $model->xmlParser->getAllFormGroups() );				
		parent::display($tpl);
	}// function

}// class
?>