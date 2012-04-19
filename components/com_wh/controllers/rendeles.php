<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerrendeles extends controllBase
{
	var $view = "rendeles";
	var $model = "rendeles";
	var $controller = "rendeles";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=rendelesek";
	var $cancelLink = "index.php?option=com_wh&controller=rendelesek";
	var $addLink = "index.php?option=com_wh&controller=rendeles&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config); 
		//$this->session();
	}// function

	function tetelmasol(){
		$cidTetel = jrequest::getvar("cidTetel", array(), "request", "array");
		$model = $this->getModel($this->model);
		$id = jrequest::getvar("id",0);
		//print_r($cidTetel);
		if($model->tetelmasol( $cidTetel )){
			$msg = "SIKERESEN MASOLVA";
		}else{
			$msg = "";
		}
		$link = "index.php?option=com_wh&controller=rendeles&Itemid={$this->Itemid}&cid[]={$id}&task=edit&fromlist=1";
		$this->setRedirect($link, $msg);
	}
	
	function visszaruosszeallit(){
		$cidTetel = jrequest::getvar("cidTetel", array(), "request", "array");
		$model = $this->getModel($this->model);
		$id = jrequest::getvar("id",0);
		//print_r($cidTetel);
		
		$msg = $model->visszaruosszeallit( $cidTetel );		
		$link = "index.php?option=com_wh&controller=rendeles&Itemid={$this->Itemid}&cid[]={$id}&task=edit&fromlist=1";
		$this->setRedirect($link, $msg);
	}
	
	function teteltorol(){
		$cidTetel = jrequest::getvar("cidTetel", array(), "request", "array");
		$model = $this->getModel($this->model);
		$id = jrequest::getvar("id",0);
		//print_r($cidTetel);
		//die;
		if($model->teteltorol( $cidTetel )){
			$msg = "TETELEK TOROLVE";
		}else{
			$msg = "";
		}
		$link = "index.php?option=com_wh&controller=rendeles&Itemid={$this->Itemid}&cid[]={$id}&task=edit&fromlist=1";
		$this->setRedirect($link, $msg);
	}
	
	function ujTetel(){
		$model= $this->getModel( $this->model );
		$rendeles_id = $model->ujTetel();
		$link = "index.php?option=com_wh&controller=rendeles&cid[]={$rendeles_id}&task=edit&fromlist=1";
		$this->setRedirect($link, jtext::_("TETEL TOROLVE") );		
	}

}//class
?>