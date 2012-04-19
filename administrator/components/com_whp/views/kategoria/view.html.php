<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whpViewkategoria extends JView
{
	function display($tpl = null)
	{
		(@$model->_data->nev) ? $nev = " - ".$model->_data->nev : $nev = "";
		JToolBarHelper::title( JText::_('KATEGORIA').$nev );
		JToolBarHelper::apply();
		JToolBarHelper::save();
		if (@$isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', JText::_('Close') );
		}
		//JToolBarHelper::back();		
	
		$model = $this->getModel(); 
		$this->assignRef('allGroups', $model->xmlParser->getAllFormGroups() );
		$this->assignRef('images', $model->getImages() );
		//$this->assignRef('allDataGroups', $model->xmlParser->getAllDataGroups() );	
		parent::display($tpl);
	}// function

}// class
?>