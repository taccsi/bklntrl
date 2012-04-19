<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllergyarto extends controllBase
{
	var $view = "gyarto";
	var $model = "gyarto";
	var $controller = "gyarto";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=gyartok";
	var $cancelLink = "index.php?option=com_wh&controller=gyartok";
	var $addLink = "index.php?option=com_wh&controller=gyarto&task=edit";

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

}//class
?>