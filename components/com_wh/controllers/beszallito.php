<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerbeszallito extends controllBase
{
	var $view = "beszallito";
	var $model = "beszallito";
	var $controller = "beszallito";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=beszallitok";
	var $cancelLink = "index.php?option=com_wh&controller=beszallitok";
	var $addLink = "index.php?option=com_wh&controller=beszallito&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

}//class
?>