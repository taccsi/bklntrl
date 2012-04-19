<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whpViewgyarto extends JView
{
	function display($tpl = null)
	{
        JToolBarHelper::title(jtext::_("gyarto"));
        JToolBarHelper::apply();
		JToolBarHelper::save();
        JToolBarHelper::cancel();
		$model = $this->getModel("gyarto");	
		$this->assignRef('model', $model);
		$this->assignRef('allGroups', $model->xmlParser->getAllFormGroups() );				
		parent::display($tpl);
	}// function

}// class
?>