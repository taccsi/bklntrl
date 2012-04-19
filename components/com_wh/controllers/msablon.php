<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllermsablon extends controllBase
{
	var $view = "msablon";
	var $model = "msablon";
	var $controller = "msablon";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=msablonok";
	var $cancelLink = "index.php?option=com_wh&controller=msablonok";
	var $addLink = "index.php?option=com_wh&controller=msablon&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function
	function torolMezo(){
		$model = $this -> getModel($this -> model);
		$model -> torolMezo();
		$id = JRequest::getVar("id", "");
		$redirect = "index.php?option=com_wh&controller=msablon&task=edit&fromlist=1&cid[]={$id}";
		$this -> setredirect($redirect, JText::_("SIKERES TORLES"));
	}
}//class
?>