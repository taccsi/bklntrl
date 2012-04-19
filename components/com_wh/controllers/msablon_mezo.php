<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllermsablon_mezo extends controllBase
{
	var $view = "msablon_mezo";
	var $model = "msablon_mezo";
	var $controller = "msablon_mezo";
	var $redirectSaveOk = "index.php?option=com_wh&controller=msablon_mezok";	
	var $cancelLink = "index.php?option=com_wh&controller=msablon_mezok";
	//var $addLink = "index.php?option=com_wh&controller=msablon_mezo&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		$this->redirectSaveOk = "index.php?option=com_wh&controller=msablon_mezok{$this->tmpl}";
		//$this->session();
	}// function

}//class
?>