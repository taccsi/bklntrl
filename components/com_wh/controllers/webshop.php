<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerwebshop extends controllBase
{
	var $view = "webshop";
	var $model = "webshop";
	var $controller = "webshop";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=webshopok";
	var $cancelLink = "index.php?option=com_wh&controller=webshopok";
	var $addLink = "index.php?option=com_wh&controller=webshop&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

}//class
?>