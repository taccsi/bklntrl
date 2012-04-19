<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpControllergyarto extends controllBase
{
	var $view = "gyarto";
	var $model = "gyarto";
	var $controller = "gyarto";	
	var $redirectSaveOk = "index.php?option=com_whp&controller=gyartok";
	var $cancelLink = "index.php?option=com_whp&controller=gyartok";
	var $addLink = "index.php?option=com_whp&controller=gyarto&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

}//class
?>