<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpControllermsablonok extends controllBase
{
	var $view = "msablonok";
	var $model = "msablonok";
	var $controller = "msablonok";
	var $addLink = "index.php?option=com_whp&controller=msablon&task=edit&fromlist=1&cid[]=";
	var $cancelLink = "index.php?option=com_whp&controller=msablonok";
	var $jTable = "whp_msablon";
	
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->session();
	}// function

}//class
?>