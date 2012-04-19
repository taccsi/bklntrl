<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerhirlevel extends controllBase
{
	var $view = "hirlevel";
	var $model = "hirlevel";
	var $controller = "hirlevel";
	var $redirectSaveOk = "index.php?option=com_wh&controller=hirlevelek";
	var $cancelLink = "index.php?option=com_wh&controller=hirlevelek"; 
	var $addLink = "index.php?option=com_wh&controller=hirlevel&task=edit";	 

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

}//class
?>