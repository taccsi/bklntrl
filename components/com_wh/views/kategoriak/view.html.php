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

class whViewkategoriak extends JView
{

	function display($tpl = null)
	{
		$model = $this->getModel();
		$this->assignRef('search', $model->getSearch("kategoria") );
		//$this->assignRef('tree', $model->getTree() );
		parent::display($tpl);
	}//function


}// class
?>