<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllermsablonok extends controllBase
{
	var $view = "msablonok";
	var $model = "msablonok";
	var $controller = "msablonok";
	var $addView = "msablon";
	var $addLink = "index.php?option=com_wh&controller=msablon&task=edit&fromlist=1&cid[]=";
	var $jTable = "wh_msablon";
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
		$model = $this->getModel("msablon");
		if($model->delete($this->jTable) ){
			$this->setRedirect("index.php?option={$this->option}&controller={$this->controller}".$this->tmpl, "sikeres torles");
		}else{
			$this->setRedirect("index.php?option={$this->option}&controller={$this->controller}".$this->tmpl, "sikertelen torles");
		}
	}
	
	function show(){
		$cid = JRequest::getVar("cid");
		$link = "index.php?option={$this->option}&controller=msablon&task=edit&cid[]={$cid[0]}&fromlist=1".$this->tmpl;
		$this->setRedirect($link);
		parent::display();
	}

	function edit()
	{
		$cid = JRequest::getVar("cid");
		$link = "index.php?option={$this->option}&controller=msablon&task=edit&cid[]={$cid[0]}&fromlist=1".$this->tmpl;
		$this->setRedirect($link);
		parent::display();
	}// function
	
	

	function cancel()
	{
		parent::display();
	}// function

}//class
?>