<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerAjax extends controllBase
{
	var $view = "ajax";
	var $model = "ajax";
	var $controller = "ajax";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=ajaxek";
	var $cancelLink = "index.php?option=com_wh&controller=ajaxek";
	var $addLink = "index.php?option=com_wh&controller=ajax&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

	function beszar_(){
		$this->display();
	}
}//class
?>