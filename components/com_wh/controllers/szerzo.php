<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerszerzo extends controllBase
{
	var $view = "szerzo";
	var $model = "szerzo";
	var $controller = "szerzo";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=szerzok";
	var $cancelLink = "index.php?option=com_wh&controller=szerzok";
	var $addLink = "index.php?option=com_wh&controller=szerzo&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

}//class
?>