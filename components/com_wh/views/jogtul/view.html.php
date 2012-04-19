<?php
defined( '_JEXEC' ) or die( '=;)' );
jimport('joomla.application.component.view');

class whViewjogtul extends JView
{
	function display($tpl = null)
	{
		$model = $this->getModel(); 
		$this->assignRef('allGroups', $model->xmlParser->getAllFormGroups() );
		$this->assignRef('nyomtatasCim', $model->getNyomtatasCim() );		
		
		//die("-----*". jrequest::getVar("tomeges", "" ) );				
		$this->assignRef('nyomtatas', $model->getJogtulKimutatas( 10 ) );	
		
		if( jrequest::getVar("tomeges", "" ) ){
			$this->assignRef('nyomtatasTomeges', $model->getJogtulKimutatasTomeges( ) );	 		
		}
		
		$this->assignRef('tomegesInputok', $model->xmlParser->getSearchArr() );		
		//getSearchArr( $js = "getJogtulKimutatas()" )											
		//$this->assignRef('allDataGroups', $model->xmlParser->getAllDataGroups() );	
		//$this->assignRef('search', $model->getSearch('jogtul') );		
		parent::display($tpl);
	}// function

}// class
?>