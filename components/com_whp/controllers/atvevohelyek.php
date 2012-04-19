<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpControlleratvevohelyek extends controllBase
{
	var $view = "atvevohelyek";
	var $model = "atvevohelyek";
	var $controller = "atvevohelyek";
	var $addLink = "index.php?option=com_whp&controller=atvevohely&task=edit&cid[]=&fromlist=1";
	var $jTable = "whp_atvevohely";	
	
	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->getatvevohelyek();
		
	}// function

	function edit(){
		$cid = JREquest::getVaR("cid", array(), "array");
		$id=$cid[0];
		$this->setRedirect("index.php?option=com_whp&controller=atvevohely&task=edit&cid[]={$id}&fromlist=1");
	}
}//class
?>