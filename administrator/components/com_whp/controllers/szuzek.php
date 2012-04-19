<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpControllerszuzek extends controllBase
{
	var $view = "szuzek";
	var $model = "szuzek";
	var $controller = "szuzek";
	var $addLink = "index.php?option=com_whp&controller=szuz&task=edit&fromlist=1&cid[]=";
	var $cancelLink = "index.php?option=com_whp&controller=szuzek";
	var $jTable = "whp_szuz";
	
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->session();
	}// function

}//class
?>