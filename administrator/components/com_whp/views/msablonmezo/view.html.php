<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whpViewmsablonmezo extends JView
{
	function display($tpl = null)
	{
        JToolBarHelper::title(jtext::_("msablonmezo"));
        JToolBarHelper::apply();
		JToolBarHelper::save();
        JToolBarHelper::cancel();
		$model = $this->getModel("msablonmezo");	
		$this->assignRef('model', $model);
		$this->assignRef('allGroups', $model->xmlParser->getAllFormGroups() );				
		parent::display($tpl);
	}// function

}// class
?>