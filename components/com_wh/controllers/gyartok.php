<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllergyartok extends controllBase
{
	var $view = "gyartok";
	var $model = "gyartok";
	var $controller = "gyartok";
	var $addView = "gyarto";
	var $addLink = "index.php?option=com_wh&controller=gyarto&task=edit&fromlist=1&cid[]=";
	var $redirectSaveOk = "index.php?option=com_wh&controller=gyartok&";		
	var $jTable = "wh_gyarto";
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
		$link = "index.php?option={$this->option}&controller=gyarto&task=edit&cid[]={$cid[0]}&fromlist=1".$this->tmpl;
		$this->setRedirect($link);
		parent::display();
	}

	function edit()
	{
		$cid = JRequest::getVar("cid");
		$link = "index.php?option={$this->option}&controller=gyarto&task=edit&cid[]={$cid[0]}&fromlist=1".$this->tmpl;
		$this->setRedirect($link);
		parent::display();
	}// function
	
	

	function cancel()
	{
		parent::display();
	}// function

}//class
?>