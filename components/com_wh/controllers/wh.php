<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerWh extends controllBase
{
	var $view = "wh";
	var $model = "wh";
	var $controller = "wh";	
	var $redirectSaveOk = "";
	var $cancelLink = "";
	var $addLink = "";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

	function logout(){
		global $mainframe;
		$mainframe->logout();
		//die("------");
		$this->setRedirect("index.php");
	}

}//class
?>