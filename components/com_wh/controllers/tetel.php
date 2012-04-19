<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllertetel extends controllBase
{
	var $view = "tetel";
	var $model = "tetel";
	var $controller = "tetel";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=tetelek";
	var $cancelLink = "index.php?option=com_wh&controller=tetelek";
	var $addLink = "index.php?option=com_wh&controller=tetel&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function
	
	function cancel(){
	}

}//class
?>