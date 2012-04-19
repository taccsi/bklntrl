<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerjogtulok extends controllBase
{
	var $view = "jogtulok";
	var $model = "jogtulok";
	var $controller = "jogtulok";
	var $addView = "jogtul";
	var $addLink = "index.php?option=com_wh&controller=jogtul&task=edit&fromlist=1&cid[]=";
	var $redirectSaveOk = "index.php?option=com_wh&controller=jogtulok";	
	var $jTable = "wh_jogtul";
	
	function __construct($config = array())
	{
		parent::__construct($config);
	}// function
	
	
	

}//class
?>