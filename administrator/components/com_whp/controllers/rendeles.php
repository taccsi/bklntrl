<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpControllerrendeles extends controllBase
{
	var $view = "rendeles";
	var $model = "rendeles";
	var $controller = "rendeles";	
	var $redirectSaveOk = "index.php?option=com_whp&controller=rendelesek";
	var $cancelLink = "index.php?option=com_whp&controller=rendelesek";
	var $addLink = "index.php?option=com_whp&controller=rendeles&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

}//class
?>