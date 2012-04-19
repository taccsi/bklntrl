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

class whViewkuponok extends JView
{

	function display($tpl = null)
	{
		$model = $this->getModel("kuponok");
		$this->assignRef("items", $model->getData() );
		$this->assignRef('pagination', $model->getPagination() );	
		$this->assignRef('search', $model->getSearch(1) );			
		parent::display($tpl);
	}//function


}// class
?>