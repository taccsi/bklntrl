<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllermuszakisablonok extends controllBase
{
	var $view = "muszakisablonok";
	var $model = "muszakisablonok";
	var $controller = "muszakisablonok";
	var $addView = "muszakisablon";
	var $addLink = "index.php?option=com_wh&controller=muszakisablon&task=edit&fromlist=1&cid[]=&layout=form";
	var $jTable = "wh_muszakisablon";
	function __construct($config = array())
	{
		$user = &JFactory::getUser();
		
		
		
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
	
	function delete(){
		$model = $this->getModel("muszakisablon");
		if($model->delete($this->jTable) ){
			$this->setRedirect("index.php?option={$this->option}&controller={$this->controller}".$this->tmpl, "sikeres torles");
		}else{
			$this->setRedirect("index.php?option={$this->option}&controller={$this->controller}".$this->tmpl, "sikertelen torles");
		}
	}
	
	function show(){
		$cid = JRequest::getVar("cid");
		$link = "index.php?option={$this->option}&controller=muszakisablon&task=edit&cid[]={$cid[0]}&fromlist=1".$this->tmpl;
		$this->setRedirect($link);
		parent::display();
	}

	function edit()
	{
		$cid = JRequest::getVar("cid");
		$link = "index.php?option={$this->option}&controller=muszakisablon&task=edit&cid[]={$cid[0]}&fromlist=1&layout=form".$this->tmpl;
		$this->setRedirect($link);
		parent::display();
	}// function
	
	

	function cancel()
	{
		parent::display();
	}// function

}//class
?>