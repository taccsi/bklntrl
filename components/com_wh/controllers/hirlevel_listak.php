<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerhirlevel_listak extends controllBase
{
	var $view = "hirlevel_listak";
	var $model = "hirlevel_listak";
	var $controller = "hirlevel_listak";
	var $addView = "hirlevel_lista";
	var $addLink = "index.php?option=com_wh&controller=hirlevel_lista&task=edit&fromlist=1&cid[]="; 
	var $redirectSaveOk = "index.php?option=com_wh&controller=hirlevel_listak";	
	var $jTable = "wh_hirlevel_lista";
		
	function __construct($config = array())
	{
		$user = &JFactory::getUser();
		/*
		$tmpl = JRequest::getVar('tmpl');
		$this->tmpl = ($tmpl) ? '&tmpl='.$tmpl : '';
		$this->addLink .= $this->tmpl;*/
		parent::__construct($config);
		//$this->session();
		
	}// function

}