<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerContent extends controllBase
{
	var $view = "content";
	var $model = "content";
	var $controller = "content";
	var $redirectSaveOk = "index.php?option=com_wh&controller=contents";
	var $cancelLink = "index.php?option=com_wh&controller=contents"; 
	var $addLink = "index.php?option=com_wh&controller=content&task=edit";	 

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

}//class
?>