<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerhirlevel_cimek extends controllBase
{
	var $view = "hirlevel_cimek";
	var $model = "hirlevel_cimek";
	var $controller = "hirlevel_cimek";
	var $addView = "hirlevel_cim";
	var $addLink = "index.php?option=com_wh&controller=hirlevel_cim&task=edit&fromlist=1&cid[]="; 
	var $redirectSaveOk = "index.php?option=com_wh&controller=hirlevel_cimek";	
	var $jTable = "wh_hirlevel_cim";
		
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