<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpControllergyartok extends controllBase
{
	var $view = "gyartok";
	var $model = "gyartok";
	var $controller = "gyartok";
	var $addLink = "index.php?option=com_whp&controller=gyarto&task=edit&fromlist=1&cid[]=";
	var $cancelLink = "index.php?option=com_whp&controller=gyartok";
	var $jTable = "whp_gyarto";
	
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->session();
	}// function

}//class
?>