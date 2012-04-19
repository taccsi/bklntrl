<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerhirlevel_lista extends controllBase
{
	var $view = "hirlevel_lista";
	var $model = "hirlevel_lista";
	var $controller = "hirlevel_lista";
	var $redirectSaveOk = "index.php?option=com_wh&controller=hirlevel_listak";
	var $cancelLink = "index.php?option=com_wh&controller=hirlevel_listak"; 
	var $addLink = "index.php?option=com_wh&controller=hirlevel_lista&task=edit";	 

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

}//class
?>