<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerkupon extends controllBase
{
	var $view = "kupon";
	var $model = "kupon";
	var $controller = "kupon";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=kuponok";
	var $cancelLink = "index.php?option=com_wh&controller=kuponok";
	var $addLink = "index.php?option=com_wh&controller=kupon&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

}//class
?>