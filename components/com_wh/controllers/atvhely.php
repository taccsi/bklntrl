<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControlleratvhely extends controllBase
{
	var $view = "atvhely";
	var $model = "atvhely";
	var $controller = "atvhely";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=atvhelyek";
	var $cancelLink = "index.php?option=com_wh&controller=atvhelyek";
	var $addLink = "index.php?option=com_wh&controller=atvhely&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

}//class
?>