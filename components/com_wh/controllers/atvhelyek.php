<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControlleratvhelyek extends controllBase
{
	var $view = "atvhelyek";
	var $model = "atvhelyek";
	var $controller = "atvhelyek";
	var $addView = "atvhely";
	var $addLink = "index.php?option=com_wh&controller=atvhely&task=edit&fromlist=1&cid[]=";
	var $redirectSaveOk = "index.php?option=com_wh&controller=atvhelyek";	
	var $jTable = "wh_atvhely";
	
	function __construct($config = array())
	{
		parent::__construct($config);
	}// function
	

}//class
?>