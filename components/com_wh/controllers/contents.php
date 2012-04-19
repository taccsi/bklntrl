<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllercontents extends controllBase
{
	var $view = "contents";
	var $model = "contents";
	var $controller = "contents";
	var $addView = "content";
	var $addLink = "index.php?option=com_wh&controller=content&task=edit&fromlist=1&cid[]="; 
	var $redirectSaveOk = "index.php?option=com_wh&controller=contents";	
	var $jTable = "wh_content";
		
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