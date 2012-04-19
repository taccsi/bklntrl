<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpControllertermekek extends controllBase
{
	var $view = "termekek";
	var $model = "termekek";
	var $controller = "termekek";
	var $addLink = "index.php?option=com_whp&controller=termek&task=edit&cid[]=&fromlist=1";
	var $jTable = "whp_termek";	
	
	function __construct($config = array())
	{
		parent::__construct($config);
		
		//$this->gettermekek();
		
	}// function

	function mentTermekek(){
		//die("-----");
		$this->session();
		
		$model = $this -> getModel($this -> model);
		//$model -> mentBeszallitoAr();
		//$model -> termekekCsopArazasa();
		$model->mentAktivAllapot();
		$model -> mentKampany();
		//$model -> mentMegvasarolhatoAllapot();
		$id = $this->getSessionVar("id");
		
		//$this->setSessionVar("beszallito_id",JRequest::getVar("beszallito_id_",""));

		$this->redirectSaveOk = "index.php?option=com_wh&controller=termekek&Itemid={$this->Itemid}";
		$this->setAllapotMegtartLink();
		$this -> setredirect($this->redirectSaveOk , JText::_("SIKERES MENTES") );		
	}

	function save(){
		//die("-----");
		$this->session();
		
		$model = $this -> getModel($this -> model);
		$model->mentAktivAllapot();
		$id = $this->getSessionVar("id");
		//$this->setSessionVar("beszallito_id",JRequest::getVar("beszallito_id_",""));
		$this->redirectSaveOk = "index.php?option=com_whp&controller=termekek&Itemid={$this->Itemid}";
		//$this->setAllapotMegtartLink();
		$this -> setredirect($this->redirectSaveOk , JText::_("SIKERES MENTES") );		
	}

	function edit(){
		$cid = JREquest::getVaR("cid", array(), "array");
		$id=$cid[0];
		$this->setRedirect("index.php?option=com_whp&controller=termek&task=edit&cid[]={$id}&fromlist=1");
	}
	
}//class
?>