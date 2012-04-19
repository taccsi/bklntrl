<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerkimutatas extends controllBase
{
	var $view = "kimutatas";
	var $model = "kimutatas";
	var $controller = "kimutatas";
	var $addView = "rendeles";
	var $addLink = "index.php?option=com_wh&controller=rendeles&task=edit&fromlist=1&cid[]=";
	var $jTable = "wh_rendeles";
	
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->session();
	}// function
}//class
?>