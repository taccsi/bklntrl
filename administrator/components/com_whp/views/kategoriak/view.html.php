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

class whpViewkategoriak extends JView
{

	function display($tpl = null)
	{
		
		JToolBarHelper::title( JText::_( 'KATEGORIAK' ), 'generic.png' );
		JToolBarHelper::preferences( 'com_whp', "500");
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();

		$model = $this->getModel();
		$this->assignRef('tree', $model->getTree() );
		$pagination = $model->getPagination();
		// push data into the template
		//$this->assignRef('search', $this->get("search"));
		$this->assignRef('pagination', $pagination);		
		parent::display($tpl);
	}//function


}// class
?>