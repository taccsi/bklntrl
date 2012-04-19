<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpControlleratvevohely extends controllBase
{
	var $view = "atvevohely";
	var $model = "atvevohely";
	var $controller = "atvevohely";	
	var $addLink = "";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->ellenorizJog();
	}// function

	function ellenorizJog(){
		$layout = jrequest::getvar("layout","");
		$model = $this->getModel($this->model);		
		if($layout == "form"){
			$cid = jrequest::getvar("cid",array(),"request", "array");
			( count($cid) ) ? $atvevohely_id = $cid[0] : $atvevohely_id = 0;
			if($atvevohely_id && !$model->ellenorizJog($atvevohely_id) ){
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
		if($model->vanatvevohelyem() ){
			$link = jroute::_("index.php?option=com_whp&controller=atvevohelyek&cond_atvevohelyeim=1&Itemid={$Itemid}");
			//$msg = JText::_("ADATAI SIKERESEN ELMENTVE")." - ".JText::_("AZ ON atvevohelyEI");		
		}else{
			$link="index.php?option=com_whp&controller=atvevohely&task=felad&Itemid={$Itemid}";
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
				$msg = JText::_( 'SIKERESEN FELADTA atvevohelyET' );
				$modelMail = $this->getModel("mail");
				$modelMail->kuldProforma($id);
			} else {
				$msg = JText::_( 'HIBA TORTENT MENTES KOZBEN' );
			}
			$link = jroute::_("index.php?option=com_whp&controller=atvevohelyek&cond_atvevohelyeim=1&Itemid={$this->Itemid}");
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