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

class whpViewtermekek extends JView
{

	function display($tpl = null)
	{
		
		$model = $this->getModel("termekek"); 
		//$model->rogzitKereses();
		//$model->setTermekekJavascript();
	 	//$this->assignRef('items', $this->get("data") );
		//print_r($items); die;
		// push data into the template
		$this->assignRef('search', $this->get("search"));
		$this->assignRef('kampanyleiras', $this->get("kampanyleiras"));
		//$this->assignRef('termekek', $this->get("termekek") );
		if ($model->task =='listCategories'){
			$this->assignRef('cont_', $model->listCategories());
		} else {
			$this->assignRef('cont_', $this->get("termekek") );
		}
		
		//$this->assignRef('catImg', $model->getCatImg() );
		$this->assignRef('utvonal', $model->getUtvonal( jrequest::getVar("cond_kategoria_id", 0) ) );
		//$this->assignRef('pagination', $model->getPagination() );
		$this->assignRef('sorrendezo', $model->getSorrendezoHTML() );
		parent::display($tpl);
	}//function



}// class
?>