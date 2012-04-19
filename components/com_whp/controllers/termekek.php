<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpControllertermekek extends controllBase
{
	var $view = "termekek";
	var $model = "termekek";
	var $controller = "termekek";
	var $addLink = "index.php?option=com_whp&controller=termek&task=edit&cid[]=&fromlist=1";
	var $jTable = "whp_termek";	
	
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->session();		
		//$this->gettermekek();
		
	}// function

	
	function xmlimport(){
		//$mode = $this->
		jrequest::setVar( "xmlImport", "igen" );
		$model = $this->getModel( $this->model );
		$model->xmlimport();
	}

	function edit(){
		$cid = JREquest::getVaR("cid", array(), "array");
		$id=$cid[0];
		$this->setRedirect("index.php?option=com_whp&controller=termek&task=edit&cid[]={$id}&fromlist=1");
	}

}//class
?>