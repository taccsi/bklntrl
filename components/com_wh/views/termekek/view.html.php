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

class whViewtermekek extends JView {

	function display($tpl = null){
		//jimport('joomla.html.toolbar');
		$model = $this->getModel("termekek"); 
	 	$items =& $this->get('Data');
	 	//print_r($items); die;
		// push data into the template
		$this->assignRef('search', $model->getSearch(1) );
		$this->assignRef('items', $items);
		
		$this->assignRef('pagination', $model->getPagination() );
		$this->assignRef('atarazas', $model->getAtarazas() );
		$this->assignRef('aktTermek', $model->getSessionVar("aktTermek") );
		$this->assignRef('termekBoxok', $model->getTermekBoxok() );
		$this->assignRef('kategoriafa', $model->getKategoriafa() );
		parent::display($tpl);
	}//function



}// class
?>