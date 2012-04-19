<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerkomment extends controllBase
{
	var $view = "komment";
	var $model = "komment";
	var $controller = "komment";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=kommentek";
	var $cancelLink = "index.php?option=com_wh&controller=kommentek";
	var $addLink = "index.php?option=com_wh&controller=komment&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

}//class
?>