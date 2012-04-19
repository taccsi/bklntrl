<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whViewarlekeres extends JView
{
	function display($tpl = null)
	{
		$model = $this->getModel("arlekeres");	
		$this->assignRef('model', $model);
		$this->assignRef('limit', $model->limit );
		$this->assignRef('limitstart', $model->limitstart );		
		$this->assignRef('total', $model->getTotal() );		
		$this->assignRef('search', $model->getSearch(2) );
		$cond_kategoria_id = JREquest::getVar("cond_kategoria_id");
		if( $cond_kategoria_id ){
			$this->assignRef('arak', $model->lekerAr() );
		}
		//$this->assignRef('allDataGroups', $model->xmlParser->getAllDataGroups() );	
		//$this->assignRef('oneletrajzok', $this->get("Oneletrajzok"));				
		parent::display($tpl);
	}// function

}// class
?>