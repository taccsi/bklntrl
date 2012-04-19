<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpControllermsablon extends controllBase
{
	var $view = "msablon";
	var $model = "msablon";
	var $controller = "msablon";	
	var $redirectSaveOk = "index.php?option=com_whp&controller=msablonok";
	var $cancelLink = "index.php?option=com_whp&controller=msablonok";
	var $addLink = "index.php?option=com_whp&controller=msablon&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

}//class
?>