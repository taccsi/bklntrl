<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerfcsoportok extends controllBase
{
	var $view = "fcsoportok";
	var $model = "fcsoportok";
	var $controller = "fcsoportok";
	var $addView = "fcsoport";
	var $addLink = "index.php?option=com_wh&controller=fcsoport&task=edit&fromlist=1&cid[]=";
	var $redirectSaveOk = "index.php?option=com_wh&controller=fcsoportok";	
	var $jTable = "";
	function __construct($config = array())
	{
		parent::__construct($config);
	}// function
	

}//class
?>