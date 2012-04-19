<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerbeallitas extends controllBase
{
	var $view = "beallitas";
	var $model = "beallitas";
	var $controller = "beallitas";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=beallitas";
	var $cancelLink = "";
	var $addLink = "";	

	function __construct($config = array())
	{
		parent::__construct($config);
		if(JRequest::getVar("task") != "edit") $this->setRedirect("index.php?option=com_wh&controller=beallitas&task=edit&cid[]=1&fromlist=1");
		//$this->session();
	}// function
	
	function keep(){
		$id = 1;
		$this->session();
		$redirect = "index.php?option=com_wh&controller=beallitas&task=edit&Itemid={$Itemid}&fromlist=1&cid[]={$id}";
		$this -> setredirect($redirect, JText::_("") );
	}
	
	function save()
	{
		$this->session();	
		$model = $this->getModel($this->model);
		$errorFields = $model->checkMandatoryFields();
		//print_r(JRequest::getVar("kat_id"));
//die($this->model);
		if(!count($errorFields) ){
			if ($id = $model->store() ) {
				$msg = JText::_( 'SIKERES MENTES' );
			} else {
			//die($errorFields);
				$msg = JText::_( 'Hiba tortent mentes kozben' );
			}
			$link = $this->redirectSaveOk;
			//$model->deleteSession();
			$this->setRedirect($link, $msg);
		}else{
			JRequest::setVar('hidemainmenu', 1);
			$msg = JText::_( 'HIBAS MEZOK' );
			$errorFields_="&errorFields[]=";
			$errorFields_.=implode("&errorFields[]=",$errorFields);
			$link = "index.php?option={$this->option}&task=edit&controller={$this->controller}&cid={$id}{$errorFields_}{$this->tmpl}";
			$this->setRedirect($link, $msg);
		}
	}
	
	function torolWs(){
		$this->session();
		global $Itemid;
		$model = $this->getModel($this -> model);
		$model -> torolWs();
		$redirect = "index.php?option=com_wh&controller=beallitas&task=keep&Itemid={$Itemid}&fromlist=1&";
		$this -> setredirect($redirect, JText::_("SIKERES TORLES") );
	}
	
	function torolFelh(){
		$this->session();
		global $Itemid;
		$model = $this->getModel($this -> model);
		$model -> torolFelh();
		$redirect = "index.php?option=com_wh&controller=beallitas&task=keep&Itemid={$Itemid}&fromlist=1&";
		$this -> setredirect($redirect, JText::_("SIKERES TORLES") );
	}
	
	function torolSzazalekKat(){
		$this->session();
		global $Itemid;
		$model = $this->getModel($this -> model);
		$model -> torolSzazalekKat();
		$redirect = "index.php?option=com_wh&controller=beallitas&task=keep&Itemid={$Itemid}&fromlist=1&";
		$this -> setredirect($redirect, JText::_("SIKERES TORLES") );
	}

}//class
?>