<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpControllertermek extends controllBase
{
	var $view = "termek";
	var $model = "termek";
	var $controller = "termek";	
	var $redirectSaveOk = "index.php?option=com_whp&controller=termekek";
	var $cancelLink = "index.php?option=com_whp&controller=termekek";
	var $addLink = "";

	function __construct($config = array())
	{
		parent::__construct($config);
	}// function
	
	function upload(){
		$model = $this->getModel($this->model);
		//$model->upload();
		$model->xmlParser->upload();
	}
}//class
?>