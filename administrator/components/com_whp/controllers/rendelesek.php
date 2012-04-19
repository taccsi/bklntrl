<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpControllerrendelesek extends controllBase
{
	var $view = "rendelesek";
	var $model = "rendelesek";
	var $controller = "rendelesek";
	var $addLink = "index.php?option=com_whp&controller=rendeles&task=edit&fromlist=1&cid[]=";
	var $cancelLink = "index.php?option=com_whp&controller=rendelesek";
	var $jTable = "whp_rendeles";
	
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->session();
	}// function

}//class
?>