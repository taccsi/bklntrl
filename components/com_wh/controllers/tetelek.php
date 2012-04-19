<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllertetelek extends controllBase
{
	var $view = "tetelek";
	var $model = "tetelek";
	var $controller = "tetelek";
	var $addLink = "index.php?option=com_wh&controller=tetel&task=edit&fromlist=1&cid[]=";
	var $cancelLink = "index.php?option=com_wh&controller=tetelek";
	var $jTable = "wh_tetel";
	
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->session();
	}// function
	
	function save(){
		$cidT = jrequest::getvar("cidT", array(), "request", "array");
		$model = $this->getModel($this->model);
		if($model->save($cidT)){
			$msg = "SIKERESEN ELMENTVE";
		}else{
			$msg = "";
		}
		$link = "index.php?option=com_wh&controller=tetelek&Itemid={$this->Itemid}";
		$this->setRedirect($link, $msg);	
	}
}//class
?>