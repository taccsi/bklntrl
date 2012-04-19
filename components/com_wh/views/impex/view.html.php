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

class whViewimpex extends JView
{

	function display($tpl = null)
	{
		$model = $this->getModel("impex"); 
	 	//$items =& $this->get('Data');
	 	//print_r($items); die;
		// push data into the template
		//$this->assignRef('search', $this->get("search"));
		//$this->assignRef('eredmeny', $this->get("eredmeny"));		
		//$this->assignRef('items', $items);
		$this->assignRef('jelentes', $model->getJelentes() );		
		
		


		parent::display($tpl);
	}//function



}// class
?>