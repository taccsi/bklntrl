<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllertermek extends controllBase
{
	var $view = "termek";
	var $model = "termek";
	var $controller = "termek";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=termekek";
	var $cancelLink = "index.php?option=com_wh&controller=termekek";
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

	function keep(){
		global $Itemid;
		$id = $this->getSessionVar("id");
		//$this->session();
		$redirect = "index.php?option=com_wh&controller=termek&task=edit&Itemid={$Itemid}&fromlist=&cid[]={$id}";
		$this -> setredirect($redirect);
	}
	
	function sorrend(){
		parent::__construct(); 
		global $Itemid;
		$model =$this->getModel($this->model);
		$model -> sorrendKep();
		$id = JRequest::getVar("id", "");
		$this->setRedirect("index.php?option=com_wh&controller={$this->controller}&task=edit&fromlist=1&cid[]={$id}&Itemid={$Itemid}");
	}
	
	function mentBeszallitoAr_(){
		$this->session();
		global $Itemid;
		$model = $this -> getModel($this -> model);
		$model -> mentBeszallitoAr();
		//$redirect = "index.php?option=com_wh&controller=termek&task=keep&fromlist=1&cid[]={$id}";
		$id = $this->getSessionVar("id");
		$redirect = "index.php?option=com_wh&controller=termek&task=keep&Itemid={$Itemid}&fromlist=1&cid[]={$id}";
		$this -> setredirect($redirect, JText::_("SIKERES MENTES") );		
	}
	
	function torolBeszallitoAr(){
		$this->session();
		global $Itemid;
		$model = $this->getModel($this -> model);
		$model -> torolBeszallitoAr();
		$id = $this->getSessionVar("id");
		$redirect = "index.php?option=com_wh&controller=termek&task=keep&Itemid={$Itemid}&fromlist=1&cid[]={$id}";
		$this -> setredirect($redirect, JText::_("SIKERES TORLES") );
	}

	function torolTermekKep(){
		$this->session();
		global $Itemid;
		$model = $this -> getModel($this -> model);
		$model -> torolTermekKep();
		$id = $this->getSessionVar("id");
		$redirect = "index.php?option=com_wh&controller=termek&task=keep&Itemid={$Itemid}&fromlist=1&cid[]={$id}";
		$this -> setredirect($redirect, JText::_("SIKERES TORLES") );
	}
	
	function torolKapcsolodoTermek(){
		$this->session();
		global $Itemid;
		$model = $this -> getModel($this -> model);
		$model -> torolKapcsolodoTermek();
		$id = $this->getSessionVar("id");
		//$id = $model->xmlparser->getAktVal("id");
		$redirect = "index.php?option=com_wh&controller=termek&task=edit&fromlist=1&cid[]={$id}&Itemid={$Itemid}";
		$this -> setredirect($redirect, JText::_("SIKERES TORLES"));
	}

	function termekvariacio(){
		global $Itemid;
		$termek_id = $this->getSessionVar("kapcsolodo_id");
		$model = $this->getModel( $this->model );
		$uj_termek_id = $model->duplikalTermek( $termek_id );
		$link = "index.php?option=com_wh&controller=termek&task=edit&fromlist=1&Itemid={$Itemid}&cid[]={$uj_termek_id}&tmpl=component";
		$this->setRedirect( $link );	
	}

}//class
?>