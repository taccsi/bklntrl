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

class whpViewkosar extends JView
{

	function display($tpl = null)
	{
		//die("vsViewP_group");
		$model = $this->getModel("kosar");	
		//getAllDataGroups
		//$this->assignRef('allGroups', $model->order->getAllFormGroups() );				
		//$this->assignRef('userData', $model->order->getUserData() );
		//$this->assignRef('orderData', $model->order->getOrderData() );				
		$this->assignRef('kosarlista', $model->getkosarLista() );	
		$this->assignRef('kosarGombok', $model->getKosarGombok() );			
		$this->assignRef('Itemid', $model->Itemid );	
		//$this->assignRef('kosarlist_html', $model->getkosarList('noform') );
		parent::display($tpl);
	}// function

}// class
?>