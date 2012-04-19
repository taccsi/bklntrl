<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerimpex extends controllBase
{
	var $view = "impex";
	var $model = "impex";
	var $controller = "impex";
	var $addLink = "index.php?option=com_wh&controller=termek&task=edit&cid[]=&fromlist=1";
	var $jTable = "";	
	
	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->getimpex();
		
	}// function

	function export(){
		$model = $this->getModel($this->model);
		$model->export();
		jrequest::setVar( "layout", "export" );
		$this->display();		
	}

	function import(){
		$model = $this->getModel($this->model);
		if($feldolgozott_sorok = $model->import() ){
			$msg = jtext::_("SIKERES_IMPORT");
		}else{
			$feldolgozott_sorok = 0;
			$msg = jtext::_("SIKERTELEN_IMPORT");		
		}
		$this->setRedirect("index.php?option=com_wh&controller=impex&import=1&feldolgozott_sorok={$feldolgozott_sorok}", $msg);
	}

	function edit(){
		$cid = JREquest::getVaR("cid", array(), "array");
		$id=$cid[0];
		$this->setRedirect("index.php?option=com_wh&controller=termek&task=edit&cid[]={$id}&fromlist=1");
	}
	
}//class
?>