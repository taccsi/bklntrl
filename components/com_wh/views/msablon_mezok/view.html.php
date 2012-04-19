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

class whViewmsablon_mezok extends JView 
{

	function display($tpl = null)
	{
		$model = $this->getModel(); 
	 	$items =& $this->get('Data');
	 	//print_r($items); die;
		$pagination =& $this->get('Pagination');
		// push data into the template
		$this->assignRef('search', $model->getSearch(1) );
		$this->assignRef('items', $items);
		$this->assignRef('pagination', $pagination);

		parent::display($tpl);
	}//function


}// class
?>