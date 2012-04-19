<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerkommentek extends controllBase{
	var $view = "kommentek";
	var $model = "kommentek";
	var $controller = "kommentek";
	var $addView = "komment";
	var $addLink = "index.php?option=com_wh&controller=komment&task=edit&fromlist=1&cid[]=";
	var $jTable = "wh_ertekeles";
	var $redirectSaveOk = "index.php?option=com_wh&controller=kommentek";
	
	function __construct($config = array())
	{
		//$user = &JFactory::getUser();
		$tmpl = JRequest::getVar('tmpl');
		$this->tmpl = ($tmpl) ? '&tmpl='.$tmpl : '';
		$this->addLink .= $this->tmpl;
		parent::__construct($config);
		//$this->session();
		
	}// function
	
	function bejelentkezes(){
		$user = JFactory::getUser();
		if($user->usertype == "Super Administrator"){
			$link = "index.php?option=com_wh&controller=kimutatas";
		}else{
			$link="index.php?option=com_wh&Itemid=2";
		}

		$this->setRedirect($link);
		parent::display();
	}
	
	function show(){
		$cid = JRequest::getVar("cid");
		$link = "index.php?option={$this->option}&controller=ertekeles&task=edit&cid[]={$cid[0]}&fromlist=1".$this->tmpl;
		$this->setRedirect($link);
		parent::display();
	}

	function edit()
	{
		$cid = JRequest::getVar("cid");
		$link = "index.php?option={$this->option}&controller=ertekeles&task=edit&cid[]={$cid[0]}&fromlist=1".$this->tmpl;
		$this->setRedirect($link);
		parent::display();
	}// function
	
	

	function cancel()
	{
		parent::display();
	}// function

}//class
?>