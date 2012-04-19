<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControlleruzletek extends controllBase
{
	var $view = "uzletek";
	var $model = "uzletek";
	var $controller = "uzletek";
	var $addView = "uzlet";
	var $addLink = "index.php?option=com_wh&controller=uzlet&task=edit&fromlist=1&cid[]=";
	var $redirectSaveOk = "index.php?option=com_wh&controller=uzletek";	
	var $jTable = "wh_uzlet";
	
	function __construct($config = array())
	{
		parent::__construct($config);
	}// function
	

}//class
?>