<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerFcsoport extends controllBase
{
	var $view = "fcsoport";
	var $model = "fcsoport";
	var $controller = "fcsoport";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=fcsoportok";
	var $cancelLink = "index.php?option=com_wh&controller=fcsoportok";
	var $addLink = "index.php?option=com_wh&controller=fcsoport&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

}//class
?>