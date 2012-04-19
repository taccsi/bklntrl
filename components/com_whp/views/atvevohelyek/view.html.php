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

class whpViewatvevohelyek extends JView
{

	function display($tpl = null)
	{
		
		$model = $this->getModel("atvevohelyek"); 
	 	$this->assignRef('items', $this->get("data") );
		//print_r($items); die;
		// push data into the template
		$this->assignRef('search', $this->get("search"));
		$this->assignRef('atvevohelyek', $this->get("atvhelyek") );
		$this->assignRef('oldalcim', $this->get("oldalcim") );		
		$this->assignRef('pagination', $model->getPagination() );

		parent::display($tpl);
	}//function



}// class
?>