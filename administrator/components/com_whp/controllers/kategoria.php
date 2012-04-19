<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpControllerkategoria extends controllBase
{
	var $view = "kategoria";
	var $model = "kategoria";
	var $controller = "kategoria";	
	var $redirectSaveOk = "index.php?option=com_whp&controller=kategoriak";	
	var $cancelLink = "index.php?option=com_whp&controller=kategoriak";
	var $addLink = "";	

	function __construct($config = array())
	{
		parent::__construct($config);
	}// function

	function edit_()
	{
		$cid = JRequest::getVar("cid");
		//print_r($cid); exit;
		$link = "index.php?option={$this->option}&controller=kategoria&task=edit&cid[]={$cid[0]}&fromlist=1";
		//die($link);
		$this->setRedirect($link);
		parent::display();
	}// function

}//class
?>