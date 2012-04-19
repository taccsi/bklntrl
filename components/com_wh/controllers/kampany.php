<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerKampany extends controllBase
{
	var $view = "kampany";
	var $model = "kampany";
	var $controller = "kampany";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=kampanyok";
	var $cancelLink = "index.php?option=com_wh&controller=kampanyok";
	var $addLink = "index.php?option=com_wh&controller=kampany&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

}//class
?>