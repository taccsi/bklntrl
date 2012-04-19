<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpControllertermek extends controllBase
{
	var $view = "termek";
	var $model = "termek";
	var $controller = "termek";	
	var $addLink = "";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->ellenorizJog();
	}// function

	function pdfFile(){
		$pdfFile = jrequest::getVar("pdfFile", "" );
		jrequest::setVar("layout", "pdffile" );
		//jrequest::setVar("pdfFile", $pdfFile );		
		$this->display();
	}

	function ellenorizJog(){
		$layout = jrequest::getvar("layout","");
		$model = $this->getModel($this->model);		
		if($layout == "form"){
			$cid = jrequest::getvar("cid",array(),"request", "array");
			( count($cid) ) ? $termek_id = $cid[0] : $termek_id = 0;
			if($termek_id && !$model->ellenorizJog($termek_id) ){
				$msg = jtext::_("FURCSA DOLGOK TORTENNEK");
				$this->setRedirect("index.php", $msg);
			}
		}
		//die;
	}
	
	function login(){
		$Itemid = $this->Itemid;
		$layout = jrequest::getvar("layout","");
		$model = $this->getModel($this->model);		
		if($model->vantermekem() ){
			$link = jroute::_("index.php?option=com_whp&controller=termekek&cond_termekeim=1&Itemid={$Itemid}");
			//$msg = JText::_("ADATAI SIKERESEN ELMENTVE")." - ".JText::_("AZ ON termekEI");		
		}else{
			$link="index.php?option=com_whp&controller=termek&task=felad&Itemid={$Itemid}";
			//$msg = JText::_("ADATAI SIKERESEN ELMENTVE");		
		}
		$this->setRedirect($link, $msg);
	}

	function apply()
	{
		$model = $this->getModel($this->model);
		$this->session();	
		$errorFields = $model->checkMandatoryFields();
		//print_r($errorFields);exit;		
		if(!count($errorFields) ){
			if ($id = $model->store()) {
				$msg = JText::_( 'SIKERES MENTES' );
			} else {
				$msg = JText::_( 'HIBAS MENTES' );
			}
			//parent::display();
		}else{
			$msg = JText::_( 'HIBAS MEZOK' );		
		}
		//print_r($_POST);exit;
		//$urlParameters = $this->getUrlParameters($model);
		$errorFields_="&errorFields[]=";
		$errorFields_ .= implode("&errorFields[]=",$errorFields);
		$task = "edit";
		if($id){
			$fromlist = 1;
		}else{
			$fromlist="";
			$id = $this->getSessionVar("id");
			if(!$id){
				//$task = "add";
			}
		}
		$link = jroute::_("index.php?option={$this->option}&task={$task}&controller={$this->controller}&cid[]={$id}&fromlist={$fromlist}{$errorFields_}{$this->tmpl}&layout=form&Itemid={$this->Itemid}");
		//die($link);
		$this->setRedirect($link, $msg);
	}// function

	function save()
	{
		$model = $this->getModel($this->model);
		$this->session();	
		$errorFields = $model->checkMandatoryFields();
		//print_r($errorFields);exit;		
		if(!count($errorFields) ){
			if ($id = $model->store() ) {
				$msg = JText::_( 'SIKERESEN FELADTA termekET' );
				$modelMail = $this->getModel("mail");
				$modelMail->kuldProforma($id);
			} else {
				$msg = JText::_( 'HIBA TORTENT MENTES KOZBEN' );
			}
			$link = jroute::_("index.php?option=com_whp&controller=termekek&cond_termekeim=1&Itemid={$this->Itemid}");
			$model->deleteSession();
			$this->setRedirect($link, $msg);
		}else{
			JRequest::setVar('hidemainmenu', 1);
			$msg = JText::_( 'HIBAS MEZOK' );
			$errorFields_="&errorFields[]=";
			$errorFields_.=implode("&errorFields[]=",$errorFields);
			$link = "index.php?option={$this->option}&task=edit&controller={$this->controller}&cid={$id}{$errorFields_}{$this->tmpl}&layout=form";
			$this->setRedirect($link, $msg);
		}
	}// function

}//class
?>