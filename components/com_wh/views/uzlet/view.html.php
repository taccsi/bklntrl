<?php
defined( '_JEXEC' ) or die( '=;)' );
jimport('joomla.application.component.view');

class whViewuzlet extends JView
{
	function display($tpl = null)
	{
		$model = $this->getModel(); 
		$this->assignRef('allGroups', $model->xmlParser->getAllFormGroups() );
		$this->assignRef('nyomtatasCim', $model->getNyomtatasCim() );		
		
		//die("-----*". jrequest::getVar("tomeges", "" ) );				
		$this->assignRef('nyomtatas', $model->getuzletKimutatas( 10 ) );	
		
		if( jrequest::getVar("tomeges", "" ) ){
			$this->assignRef('nyomtatasTomeges', $model->getuzletKimutatasTomeges( ) );	 		
		}
		
		$this->assignRef('tomegesInputok', $model->xmlParser->getSearchArr() );		
		//getSearchArr( $js = "getuzletKimutatas()" )											
		//$this->assignRef('allDataGroups', $model->xmlParser->getAllDataGroups() );	
		//$this->assignRef('search', $model->getSearch('uzlet') );		
		parent::display($tpl);
	}// function

}// class
?>