<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerrendelesek extends controllBase
{
	var $view = "rendelesek";
	var $model = "rendelesek";
	var $controller = "rendelesek";
	var $addView = "rendeles";
	var $addLink = "index.php?option=com_wh&controller=rendeles&task=edit&fromlist=1&cid[]=";
	var $jTable = "wh_rendeles";
	var $redirectSaveOk = "index.php?option=com_wh&controller=rendelesek";
		
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->session();
	}// function
	
	function rendbehoz(){
		$model = $this->getModel($this->model);
		$model->rendbehoz();
		$this->setRedirect($this->redirectSaveOk);
	}
	
	function osszeallitPickPackSzallitmany(){
		$cid = jrequest::getvar("cid", array(), "request", "array");
		$model = $this->getModel($this->model);
		if($model->osszeallitPickPackSzallitmany($cid)){
			$msg = "SZALLITMANY_SIKERESEN_ELKULDVE";
		}else{
			$msg = "";
		}
		$link = "index.php?option=com_wh&controller=rendelesek&Itemid={$this->Itemid}";
		$this->setRedirect($link, $msg);
	}
	
	function mentFiktivRendelesek(){
		$model = $this->getModel($this->model);
		if( $model->mentFiktivRendelesek( ) ){
			
		}
		$msg  = jtext::_("SIKERESEN_ELMENTVE");
		$this->setAllapotMegtartLink();
		$this -> setredirect($this->redirectSaveOk , $msg );
	}
	
	function masol(){
		$cid = jrequest::getvar("cid", array(), "request", "array");
		$model = $this->getModel($this->model);
		if($model->masol($cid)){
			$msg = "SIKERESEN MASOLVA";
		}else{
			$msg = "";
		}
		$link = "index.php?option=com_wh&controller=rendelesek&Itemid={$this->Itemid}";
		$this->setRedirect($link, $msg);
	}
	

}//class
?>