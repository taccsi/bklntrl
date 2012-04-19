<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerarlekeres extends controllBase
{
	var $view = "arlekeres";
	var $model = "arlekeres";
	var $controller = "arlekeres";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=arlekeresok";
	var $cancelLink = "index.php?option=com_wh&controller=arlekeresok";
	var $addLink = "index.php?option=com_wh&controller=arlekeres&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

}//class
?>