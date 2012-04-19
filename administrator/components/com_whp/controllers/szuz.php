<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpControllerszuz extends controllBase
{
	var $view = "szuz";
	var $model = "szuz";
	var $controller = "szuz";	
	var $redirectSaveOk = "index.php?option=com_whp&controller=szuzek";
	var $cancelLink = "index.php?option=com_whp&controller=szuzek";
	var $addLink = "index.php?option=com_whp&controller=szuz&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

}//class
?>