<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllermuszakisablon extends controllBase
{
	var $view = "muszakisablon";
	var $model = "muszakisablon";
	var $controller = "muszakisablon";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=muszakisablonok";
	var $cancelLink = "index.php?option=com_wh&controller=muszakisablonok";
	var $addLink = "index.php?option=com_wh&controller=muszakisablon&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

}//class
?>