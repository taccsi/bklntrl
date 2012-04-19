<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerhirlevelek extends controllBase
{
	var $view = "hirlevelek";
	var $model = "hirlevelek";
	var $controller = "hirlevelek";
	var $addView = "hirlevel";
	var $addLink = "index.php?option=com_wh&controller=hirlevel&task=edit&fromlist=1&cid[]="; 
	var $redirectSaveOk = "index.php?option=com_wh&controller=hirlevelek";	
	var $jTable = "wh_hirlevel";
		
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