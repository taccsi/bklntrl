<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerkampanyok extends controllBase {
	var $view = "kampanyok";
	var $model = "kampanyok";
	var $controller = "kampanyok";
	var $addView = "kampany";
	var $addLink = "index.php?option=com_wh&controller=kampany&task=edit&fromlist=1&cid[]=";
	var $redirectSaveOk = "index.php?option=com_wh&controller=kampanyok";	
	var $jTable = "wh_kampany";		
	function __construct($config = array())
	{
		parent::__construct($config);
	}// function
}