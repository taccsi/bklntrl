<?php
/**
 * @version $Id: header.php 789 2009-01-26 15:56:03Z elkuku $
 * @package    st
 * @subpackage
 * @author     EasyJoomla {@link http://www.easy-joomla.org Easy-Joomla.org}
 * @author     Fuli Szabolcs {@link http://www.trifid.hu}
 * @author     Created on 09-Sep-09
 */

//--No direct access
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whViewkimutatas extends JView 
{

	function display($tpl = null)
	{
		$model = $this->getModel();
		$this->assignRef('cont_', $model->getData() );		
		
		
		
		
		
		$paneArr = $model->paneArr;
		
		$this->assignRef('paneArr', $model->paneArr );	
		foreach($paneArr as $a){
			if(method_exists($model, $a) ){
				$this->assignRef($a, $model->$a() );
			}else{
				$c_ = jtext::_("n/a");
				$this->assignRef($a, $c_ );
			}
		}
		parent::display($tpl);
	}//function


}// class
?>