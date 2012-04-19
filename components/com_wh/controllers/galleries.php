<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllergalleries extends controllBase
{
	var $view = "galleries";
	var $model = "galleries";
	var $controller = "galleries";
	var $addView = "galleries";
	var $addLink = "index.php?option=com_wh&controller=gallery&task=edit&fromlist=1&cid[]=";
	var $redirectSaveOk = "index.php?option=com_wh&controller=galleries";	
	var $jTable = "wh_gallery";
		
	function __construct($config = array())	{
		$user = &JFactory::getUser();
		/*
		$tmpl = JRequest::getVar('tmpl');
		$this->tmpl = ($tmpl) ? '&tmpl='.$tmpl : '';
		$this->addLink .= $this->tmpl;*/
		parent::__construct($config);
		//$this->session();
		
	}// function
}