<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpControllermsablonmezok extends controllBase
{
	var $view = "msablonmezok";
	var $model = "msablonmezok";
	var $controller = "msablonmezok";
	var $addLink = "index.php?option=com_whp&controller=msablonmezo&task=edit&fromlist=1&cid[]=";
	var $cancelLink = "index.php?option=com_whp&controller=msablonmezok";
	var $jTable = "whp_msablonmezo";
	
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->session();
	}// function

}//class
?>