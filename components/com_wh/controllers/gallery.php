<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllergallery extends controllBase
{
	var $view = "gallery";
	var $model = "gallery";
	var $controller = "gallery";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=galleries";
	var $cancelLink = "index.php?option=com_wh&controller=galleries";
	var $addLink = "index.php?option=com_wh&controller=gallery&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

}//class
?>